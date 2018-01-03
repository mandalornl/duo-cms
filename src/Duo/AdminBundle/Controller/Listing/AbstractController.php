<?php

namespace Duo\AdminBundle\Controller\Listing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Duo\AdminBundle\Configuration\FieldInterface;
use Duo\AdminBundle\Configuration\ORM\FilterInterface;
use Duo\AdminBundle\Event\TwigEvent;
use Duo\AdminBundle\Event\TwigEvents;
use Duo\AdminBundle\Helper\PaginatorHelper;
use Duo\AdminBundle\Twig\TwigContext;
use Duo\BehaviorBundle\Controller;
use Duo\BehaviorBundle\Entity;
use Duo\BehaviorBundle\Event\VersionEvent;
use Duo\BehaviorBundle\Event\VersionEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as FrameworkController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends FrameworkController
{
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
	 * Pre decorate entity
	 *
	 * @param object $entity
	 *
	 * @return $this
	 */
	protected function preDecorateEntity($entity)
	{
		if ($entity instanceof Entity\TranslateInterface)
		{
			$locales = $this->getParameter('locales');
			if (empty($locales))
			{
				$locales = [$this->getParameter('locale')];
			}

			foreach ($locales as $locale)
			{
				$entity->translate($locale);
			};

			$entity->mergeNewTranslations();
		}

		return $this;
	}

	/**
	 * Post decorate entity
	 *
	 * @param object $entity
	 *
	 * @return $this
	 */
	protected function postDecorateEntity($entity)
	{
		if ($entity instanceof Entity\TranslateInterface)
		{
			$entity->mergeNewTranslations();
		}

		return $this;
	}

	/**
	 * Index view
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	protected function doIndexAction(Request $request): Response
	{
		return $this->render($this->getListTemplate(), [
			'paginator' => $this->getPaginator(
				$request->get('page'),
				$request->get('limit')
			),
			'list' => array_merge([
				'type' => $this->getListType(),
				'localizedType' => $this->get('translator')->trans("duo.admin.listing.type.{$this->getListType()}"),
				'filters' => $this->filters,
				'fields' => $this->fields,
			], $this->getListBehaviors())
		]);
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

		// only fetch latest version of entities
		if ($reflectionClass->implementsInterface(Entity\VersionInterface::class))
		{
			$builder->andWhere('e.version = e.id');
		}

		// don't fetch deleted entities
		if ($reflectionClass->implementsInterface(Entity\DeleteInterface::class))
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		// order by weight
		if ($reflectionClass->implementsInterface(Entity\SortInterface::class))
		{
			$builder
				->orderBy('e.weight', 'ASC')
				->addOrderBy('e.id', 'ASC');
		}
		else
		{
			// order by last modified
			if ($reflectionClass->implementsInterface(Entity\TimeStampInterface::class))
			{
				$builder
					->orderBy('e.modifiedAt', 'DESC')
					->addOrderBy('e.id', 'ASC');
			}
		}

		return (new PaginatorHelper($builder))
			->setPage($page)
			->setLimit($limit)
			->setAdjacent(2)
			->create();
	}

	/**
	 * Add entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doAddAction(Request $request)
	{
		$class = $this->getEntityClassName();
		$entity = new $class();
		$this->preDecorateEntity($entity);

		$form = $this->createForm($this->getFormClassName(), $entity, [
			'attr' => [
				'class' => 'form-add'
			]
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$this->postDecorateEntity($entity);

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
		}

		return $this->render($this->getAddTemplate(), [
			'form' => $form->createView(),
			'entity' => $entity,
			'type' => $this->getListType(),
			'localizedType' => $this->get('translator')->trans("duo.admin.listing.type.{$this->getListType()}"),
		]);
	}

	/**
	 * Edit entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doEditAction(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		// handle entity versioning
		if ($entity instanceof Entity\VersionInterface)
		{
			// redirect to latest version
			if ($entity->getVersion() !== $entity)
			{
				return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_edit", [
					'id' => $entity->getVersion()->getId()
				]);
			}

			$clone = clone $entity;

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
				// post submit state
				$postSubmitState = serialize($clone);

				// check whether or not entity was modified
				if (strcmp($preSubmitState, $postSubmitState) !== 0)
				{
					// dispatch onClone event
					$this->get('event_dispatcher')->dispatch(VersionEvents::CLONE, new VersionEvent($clone, $entity));

					$em = $this->getDoctrine()->getManager();
					$em->persist($clone);
					$em->flush();
				}

				return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
			}

			$context = new TwigContext([
				'form' => $form->createView(),
				'entity' => $clone,
				'type' => $this->getListType(),
				'localizedType' => $this->get('translator')->trans("duo.admin.listing.type.{$this->getListType()}"),
			]);

			// dispatch onTwigContext event
			$this->get('event_dispatcher')->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

			return $this->render($this->getEditTemplate(), (array)$context);
		}
		else
		{
			$form = $this->createForm($this->getFormClassName(), $entity, [
				'attr' => [
					'class' => 'form-edit'
				]
			]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
			}

			$context = new TwigContext([
				'form' => $form->createView(),
				'entity' => $entity,
				'type' => $this->getListType(),
				'localizedType' => $this->get('translator')->trans("duo.admin.listing.type.{$this->getListType()}")
			]);

			// dispatch onTwigContext event
			$this->get('event_dispatcher')->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

			return $this->render($this->getEditTemplate(), (array)$context);
		}
	}

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	protected function doDestroyAction(Request $request, int $id)
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
					'success' => true
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
	}

	/**
	 * Destroy multiple
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * TODO: implement
	 */
	protected function doDestroyMultipleAction(Request $request)
	{
//		$ids = $request->get('ids');
//
//		/**
//		 * @var EntityManager $em
//		 */
//		$em = $this->getDoctrine()->getManager();
//
//		$em->createQueryBuilder()
//			->delete($this->getEntityClassName(), 'e')
//			->where('e.id IN(:ids)')
//			->setParameter('ids', $ids)
//			->getQuery()->execute();
//
		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
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
	 * Get route prefix
	 *
	 * @return string
	 */
	abstract protected function getListType(): string;

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
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function destroyAction(Request $request, int $id);
}