<?php

namespace Duo\AdminBundle\Controller\Listing;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Duo\AdminBundle\Configuration\FieldInterface;
use Duo\AdminBundle\Configuration\ORM\FilterInterface;
use Duo\AdminBundle\Controller\RoutePrefixTrait;
use Duo\AdminBundle\Event\ListingEvent;
use Duo\AdminBundle\Event\ListingEvents;
use Duo\AdminBundle\Event\TwigEvent;
use Duo\AdminBundle\Event\TwigEvents;
use Duo\AdminBundle\Helper\PaginatorHelper;
use Duo\AdminBundle\Twig\TwigContext;
use Duo\BehaviorBundle\Controller;
use Duo\BehaviorBundle\Entity;
use Duo\BehaviorBundle\Event\RevisionEvent;
use Duo\BehaviorBundle\Event\RevisionEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as FrameworkController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends FrameworkController
{
	use RoutePrefixTrait;

	/**
	 * @var Collection
	 */
	private $filters;

	/**
	 * @var Collection
	 */
	private $fields;

	/**
	 * AbstractController constructor
	 */
	public function __construct()
	{
		$this->filters = new ArrayCollection();
		$this->fields = new ArrayCollection();

		$this->defineFilters();
		$this->defineFields();
	}

	/**
	 * Add filter
	 *
	 * @param FilterInterface $filter
	 *
	 * @return $this
	 */
	public function addFilter(FilterInterface $filter)
	{
		$this->filters[] = $filter;

		return $this;
	}

	/**
	 * Remove filter
	 *
	 * @param FilterInterface $filter
	 *
	 * @return $this
	 */
	public function removeFilter(FilterInterface $filter)
	{
		$this->filters->removeElement($filter);

		return $this;
	}

	/**
	 * Get filters
	 *
	 * @return ArrayCollection
	 */
	public function getFilters()
	{
		return $this->filters;
	}

	/**
	 * Add field
	 *
	 * @param FieldInterface $field
	 *
	 * @return $this
	 */
	public function addField(FieldInterface $field)
	{
		$this->fields[] = $field;

		return $this;
	}

	/**
	 * Remove field
	 *
	 * @param FieldInterface $field
	 *
	 * @return $this
	 */
	public function removeField(FieldInterface $field)
	{
		$this->fields->removeElement($field);

		return $this;
	}

	/**
	 * Get fields
	 *
	 * @return ArrayCollection
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * Index view
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws AnnotationException
	 */
	protected function doIndexAction(Request $request): Response
	{
		return $this->render($this->getListTemplate(), (array)$this->getDefaultContext([
			'paginator' => $this->getPaginator(
				$request->get('page'),
				$request->get('limit')
			),
			'list' => array_merge([
				'filters' => $this->filters,
				'fields' => $this->fields,
			], $this->getListBehaviors())
		]));
	}

	/**
	 * Get default context
	 *
	 * @param array $context [optional]
	 *
	 * @return TwigContext
	 *
	 * @throws AnnotationException
	 */
	protected function getDefaultContext(array $context = []): TwigContext
	{
		$context = array_merge([
			'menu' => $this->get('duo.admin.menu_builder')->createView(),
			'routePrefix' => $this->getRoutePrefix(),
			'type' => $this->getType()
		], $context);

		return new TwigContext($context);
	}

	/**
	 * Get list behaviors
	 *
	 * @return array
	 */
	private function getListBehaviors()
	{
		$reflectionClass = new \ReflectionClass($this);

		return [
			'isSortable' => $reflectionClass->implementsInterface(Controller\SortInterface::class),
			'isDeletable' => $reflectionClass->implementsInterface(Controller\DeleteInterface::class)
		];
	}

	/**
	 * Get paginator
	 *
	 * @param int $page
	 * @param int $limit
	 *
	 * @return PaginatorHelper
	 */
	protected function getPaginator(int $page = null, int $limit = null): PaginatorHelper
	{
		$reflectionClass = new \ReflectionClass($this->getEntityClassName());

		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		$builder = $repository->createQueryBuilder('e');

		// only fetch latest revision of entities
		if ($reflectionClass->implementsInterface(Entity\RevisionInterface::class))
		{
			$builder->andWhere('e.revision = e.id');
		}

		// don't fetch deleted entities
		if ($reflectionClass->implementsInterface(Entity\DeleteInterface::class))
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		// order by last modified
		if ($reflectionClass->implementsInterface(Entity\TimeStampInterface::class))
		{
			$builder->addOrderBy('e.modifiedAt', 'DESC');
		}

		// order by weight
		if ($reflectionClass->implementsInterface(Entity\SortInterface::class))
		{
			$builder->addOrderBy('e.weight', 'ASC');
		}

		$builder->addOrderBy('e.id', 'ASC');

		return (new PaginatorHelper($builder))
			->setPage($page)
			->setLimit($limit)
			->setAdjacent(2)
			->createView();
	}

	/**
	 * Add entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doAddAction(Request $request)
	{
		$class = $this->getEntityClassName();
		$entity = new $class();

		/**
		 * @var EventDispatcherInterface $eventDispatcher
		 */
		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre add event
		$eventDispatcher->dispatch(ListingEvents::PRE_ADD, new ListingEvent($entity));

		$form = $this->createForm($this->getFormClassName(), $entity, [
			'attr' => [
				'class' => 'form-add'
			]
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post add event
			$eventDispatcher->dispatch(ListingEvents::POST_ADD, new ListingEvent($entity));

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		return $this->render($this->getAddTemplate(), (array)$this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $entity
		]));
	}

	/**
	 * Edit entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doEditAction(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		// handle entity revision
		if ($entity instanceof Entity\RevisionInterface)
		{
			return $this->handleEditRevisionRequest($request, $entity);
		}

		return $this->handleEditEntityRequest($request, $entity);
	}

	/**
	 * Handle edit entity request
	 *
	 * @param Request $request
	 * @param object $entity
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	protected function handleEditEntityRequest(Request $request, $entity)
	{
		/**
		 * @var EventDispatcherInterface $eventDispatcher
		 */
		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre edit event
		$eventDispatcher->dispatch(ListingEvents::PRE_EDIT, new ListingEvent($entity));

		$form = $this->createForm($this->getFormClassName(), $entity, [
			'attr' => [
				'class' => 'form-edit'
			]
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post edit event
			$eventDispatcher->dispatch(ListingEvents::POST_EDIT, new ListingEvent($entity));

			try
			{
				/**
				 * @var EntityManager $em
				 */
				$em = $this->getDoctrine()->getManager();

				// check whether or not entity is locked
				if ($entity instanceof Entity\VersionInterface)
				{
					$em->lock($entity, LockMode::OPTIMISTIC, $entity->getVersion());
				}

				$em->persist($entity);
				$em->flush();

				$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));
			}
			catch (OptimisticLockException $e)
			{
				$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.locked'));
			}

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		$context = $this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $entity
		]);

		// dispatch twig context event
		$eventDispatcher->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

		return $this->render($this->getEditTemplate(), (array)$context);
	}

	/**
	 * Handle edit revision request
	 *
	 * @param Request $request
	 * @param Entity\RevisionInterface $entity
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	protected function handleEditRevisionRequest(Request $request, Entity\RevisionInterface $entity)
	{
		$clone = clone $entity;

		/**
		 * @var EventDispatcherInterface $eventDispatcher
		 */
		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre edit event
		$eventDispatcher->dispatch(ListingEvents::PRE_EDIT, new ListingEvent($clone));

		// pre submit state
		$preSubmitState = serialize($clone);

		$form = $this->createForm($this->getFormClassName(), $clone, [
			'attr' => [
				'class' => 'form-edit'
			]
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post edit event
			$eventDispatcher->dispatch(ListingEvents::POST_EDIT, new ListingEvent($clone));

			// post submit state
			$postSubmitState = serialize($clone);

			// check whether or not entity was modified, don't force unchanged revision
			if (strcmp($preSubmitState, $postSubmitState) !== 0)
			{
				// dispatch onClone event
				$eventDispatcher->dispatch(RevisionEvents::CLONE, new RevisionEvent($clone, $entity));

				try
				{
					/**
					 * @var EntityManager $em
					 */
					$em = $this->getDoctrine()->getManager();

					// check whether or not entity is locked
					if ($clone instanceof Entity\VersionInterface)
					{
						$em->lock($entity, LockMode::OPTIMISTIC, $clone->getVersion());
					}

					$em->persist($clone);
					$em->flush();

					$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));

					return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
				}
				catch (OptimisticLockException $e)
				{
					$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.locked'));
				}
			}
		}

		// redirect to latest revision
		if ($entity->getRevision() !== $entity)
		{
			return $this->redirectToRoute("{$this->getRoutePrefix()}_edit", [
				'id' => $entity->getRevision()->getId()
			]);
		}

		$context = $this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $clone
		]);

		// dispatch twig context event
		$eventDispatcher->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

		return $this->render($this->getEditTemplate(), (array)$context);
	}

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doDestroyAction(Request $request, int $id = null)
	{
		if ($id === null)
		{
			return $this->handleMultiDestroyRequest($request);
		}

		return $this->handleDestroyRequest($request, $id);
	}

	/**
	 * Handle destroy request
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return JsonResponse|RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	private function handleDestroyRequest(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($entity);
		$em->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' => true,
					'message' => $this->get('translator')->trans('duo.admin.listing.alert.delete_success')
				]
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.delete_success'));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Handle multi destroy request
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse|RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	private function handleMultiDestroyRequest(Request $request)
	{
		if (!count($ids = $request->get('ids')))
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return new JsonResponse([
					'result' => [
						'success' => false,
						'message' => $this->get('translator')->trans('duo.admin.listing.alert.no_items')
					]
				]);
			}

			$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.no_items'));
		}
		else
		{
			/**
			 * @var EntityManager $em
			 */
			$em = $this->getDoctrine()->getManager();

			$em->createQueryBuilder()
				->delete($this->getEntityClassName(), 'e')
				->where('e.id IN(:ids)')
				->setParameter('ids', $ids)
				->getQuery()
				->execute();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return new JsonResponse([
					'result' => [
						'success' => true,
						'message' => $this->get('translator')->trans('duo.admin.listing.alert.delete_success')
					]
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.delete_success'));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Get list template
	 *
	 * @return string
	 */
	protected function getListTemplate(): string
	{
		return '@DuoAdmin/Listing/list.html.twig';
	}

	/**
	 * Get add template
	 *
	 * @return string
	 */
	protected function getAddTemplate(): string
	{
		return '@DuoAdmin/Listing/add.html.twig';
	}

	/**
	 * Get edit template
	 *
	 * @return string
	 */
	protected function getEditTemplate(): string
	{
		return '@DuoAdmin/Listing/edit.html.twig';
	}

	/**
	 * Entity not found
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	protected function entityNotFound(Request $request, int $id): JsonResponse
	{
		$error = "Entity '{$this->getEntityClassName()}::{$id}' not found";

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' => false,
					'error' => $error
				]
			]);
		}

		throw $this->createNotFoundException($error);
	}

	/**
	 * Redirect to referer
	 *
	 * @param string $fallbackUrl [optional]
	 * @param Request $request [optional]
	 *
	 * @return RedirectResponse
	 */
	protected function redirectToReferer(string $fallbackUrl, Request $request = null): RedirectResponse
	{
		if ($request === null)
		{
			$request = $this->get('request_stack')->getCurrentRequest();
		}

		// use fallback route if referer is missing
		if ($request->headers->get('referer') === null)
		{
			return $this->redirect($fallbackUrl);
		}

		return $this->redirect($request->headers->get('referer'));
	}

	/**
	 * Get entity class name
	 *
	 * @return string
	 */
	abstract protected function getEntityClassName(): string;

	/**
	 * Get form class name
	 *
	 * @return string
	 */
	abstract protected function getFormClassName(): string;

	/**
	 * Get type
	 *
	 * @return string
	 */
	abstract protected function getType(): string;

	/**
	 * Define filters
	 */
	abstract protected function defineFilters(): void;

	/**
	 * Define fields
	 */
	abstract protected function defineFields(): void;

	/**
	 * Index view
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	abstract public function indexAction(Request $request): Response;

	/**
	 * Add entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function addAction(Request $request);

	/**
	 * Edit entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function editAction(Request $request, int $id);

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function destroyAction(Request $request, int $id = null);
}