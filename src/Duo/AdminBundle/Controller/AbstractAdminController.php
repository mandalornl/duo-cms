<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Duo\AdminBundle\Configuration\FieldInterface;
use Duo\AdminBundle\Configuration\ORM\FilterInterface;
use Duo\AdminBundle\Controller\Behavior\SortTrait;
use Duo\AdminBundle\Entity\Behavior\CloneInterface;
use Duo\AdminBundle\Entity\Behavior\VersionInterface;
use Duo\AdminBundle\Entity\Behavior\PublishInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeleteInterface;
use Duo\AdminBundle\Entity\Behavior\TranslateInterface;
use Duo\AdminBundle\Entity\Behavior\TreeInterface;
use Duo\AdminBundle\Entity\Behavior\ViewInterface;
use Duo\AdminBundle\Event\Behavior\VersionEvent;
use Duo\AdminBundle\Event\Behavior\VersionEvents;
use Duo\AdminBundle\Helper\ReflectionClassHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAdminController extends Controller
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
	 * AbstractAdminController constructor
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
		if ($entity instanceof TranslateInterface)
		{
			foreach ($this->getParameter('locales') as $locale)
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
		if ($entity instanceof TranslateInterface)
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
		$reflectionClass = new \ReflectionClass($this);

		return $this->render($this->getListTemplate(), [
			'list' => [
				'type' => $this->getListType(),
				'localizedType' => $this->get('translator')->trans("duo.type.{$this->getListType()}"),
				'filters' => $this->filters,
				'fields' => $this->fields,
				'entities' => $this->getEntities(),
				'isSortable' => ReflectionClassHelper::hasTrait($reflectionClass, SortTrait::class)
			]
		]);
	}

	/**
	 * Get entities
	 *
	 * @return array
	 */
	protected function getEntities(): array
	{
		$criteria = [];
		$orderBy = null;

		$reflectionClass = new \ReflectionClass($this->getEntityClassName());

		// order by weight
		if ($reflectionClass->implementsInterface(TreeInterface::class))
		{
			$orderBy = [
				'weight' => 'ASC',
				'id' => 'ASC'
			];
		}

		// only fetch latest version of entities
		if ($reflectionClass->implementsInterface(VersionInterface::class))
		{
			/**
			 * @var EntityManager $em
			 */
			$em = $this->getDoctrine()->getManager();

			$tableName = $em->getClassMetadata($this->getEntityClassName())->getTableName();

			$rsm = new ResultSetMapping();
			$rsm->addScalarResult('id', 'id', 'integer');

			$sql = <<<SQL
SELECT version_id id FROM {$tableName} GROUP BY version_id
SQL;

			$ids = array_column($em->createNativeQuery($sql, $rsm)->getScalarResult(), 'id');
			if (!count($ids))
			{
				return [];
			}

			$criteria = [
				'id' => $ids
			];
		}

		return $this->getDoctrine()->getRepository($this->getEntityClassName())->findBy($criteria, $orderBy);
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

		$form = $this->createForm($this->getFormClassName(), $entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$this->postDecorateEntity($entity);

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirectToRoute("duo_admin_{$this->getListType()}_list");
		}

		return $this->render($this->getAddTemplate(), array_merge([
			'form' => $form->createView(),
			'entity' => $entity,
			'type' => $this->getListType(),
			'localizedType' => $this->get('translator')->trans("duo.type.{$this->getListType()}"),
		], $this->getEntityBehaviors($entity)));
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
			return $this->entityNotFound($id);
		}

		// handle entity versioning
		if ($entity instanceof VersionInterface)
		{
			$clone = clone $entity;

			// pre submit state
			$preSubmitState = serialize($clone);

			$form = $this->createForm($this->getFormClassName(), $clone);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid())
			{
				// post submit state
				$postSubmitState = serialize($clone);

				// check whether or not entity was modified
				if (strcmp($preSubmitState, $postSubmitState) !== 0)
				{
					$eventDispatcher = $this->get('event_dispatcher');

					// dispatch pre clone event
					$eventDispatcher->dispatch(VersionEvents::PRE_CLONE, new VersionEvent($clone, $entity));

					$em = $this->getDoctrine()->getManager();
					$em->persist($clone);
					$em->flush();

					// dispatch post clone event
					$eventDispatcher->dispatch(VersionEvents::POST_CLONE, new VersionEvent($clone, $entity));
				}

				return $this->redirectToRoute("duo_admin_{$this->getListType()}_list");
			}

			return $this->render($this->getEditTemplate(), array_merge([
				'form' => $form->createView(),
				'entity' => $clone,
				'type' => $this->getListType(),
				'localizedType' => $this->get('translator')->trans("duo.type.{$this->getListType()}"),
			], $this->getEntityBehaviors($clone)));
		}
		else
		{
			$form = $this->createForm($this->getFormClassName(), $entity);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirectToRoute("duo_admin_{$this->getListType()}_list");
			}

			return $this->render($this->getEditTemplate(), array_merge([
				'form' => $form->createView(),
				'entity' => $entity,
				'type' => $this->getListType(),
				'localizedType' => $this->get('translator')->trans("duo.type.{$this->getListType()}"),
			], $this->getEntityBehaviors($entity)));
		}
	}

	/**
	 * Get entity behaviors
	 *
	 * @param object $entity
	 *
	 * @return array
	 */
	private function getEntityBehaviors($entity): array
	{
		return [
			'isTranslatable' => $entity instanceof TranslateInterface,
			'isPublishable' => $entity instanceof PublishInterface,
			'isSoftDeletable' => $entity instanceof SoftDeleteInterface,
			'isViewable' => $entity instanceof ViewInterface,
			'isCloneable' => $entity instanceof CloneInterface,
			'isVersionable' => $entity instanceof VersionInterface
		];
	}

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doDestroyAction(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id, $request);
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($entity);
		$em->flush();

		if ($request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' => true
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_{$this->getListType()}_list");
	}

	/**
	 * Get list template
	 *
	 * @return string
	 */
	protected function getListTemplate(): string
	{
		return '@DuoAdmin/List/list.html.twig';
	}

	/**
	 * Get add template
	 *
	 * @return string
	 */
	protected function getAddTemplate(): string
	{
		return '@DuoAdmin/List/add.html.twig';
	}

	/**
	 * Get edit template
	 *
	 * @return string
	 */
	protected function getEditTemplate(): string
	{
		return '@DuoAdmin/List/edit.html.twig';
	}

	/**
	 * Entity not found
	 *
	 * @param int $id
	 * @param Request $request [optional]
	 *
	 * @return Response|JsonResponse
	 */
	protected function entityNotFound(int $id, Request $request = null)
	{
		$error = "Entity of type '{$this->getEntityClassName()}' with id '{$id}' not found";

		if ($request !== null && $request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' => false,
					'error' => $error
				]
			]);
		}

		return new Response($error, 404);
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
	 * @return Response|RedirectResponse|JsonResponse
	 */
	abstract public function destroyAction(Request $request, int $id);
}