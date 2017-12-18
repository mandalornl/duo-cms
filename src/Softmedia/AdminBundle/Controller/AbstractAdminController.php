<?php

namespace Softmedia\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Softmedia\AdminBundle\Configuration\FieldInterface;
use Softmedia\AdminBundle\Configuration\ORM\FilterInterface;
use Softmedia\AdminBundle\Entity\Behavior\CloneableInterface;
use Softmedia\AdminBundle\Entity\Behavior\PublishableInterface;
use Softmedia\AdminBundle\Entity\Behavior\SoftDeletableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TranslatableInterface;
use Softmedia\AdminBundle\Entity\Behavior\ViewableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
			'list' => [
				'type' => $this->getListType(),
				'filters' => $this->filters,
				'fields' => $this->fields,
				'entities' => $this->getEntities()
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
		$reflectionClass = new \ReflectionClass($this->getEntityClassName());
		if ($reflectionClass->implementsInterface(CloneableInterface::class))
		{
			// TODO: implement
		}

		return $this->getDoctrine()->getRepository($this->getEntityClassName())->findAll();
	}

	/**
	 * Add entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doAddIndex(Request $request)
	{
		$class = $this->getEntityClassName();
		$entity = new $class();
		$this->preDecorateEntity($entity);

		$form = $this->createForm($this->getFormClassName(), $entity);
		$form->handleRequest($request);

		if ($form->isSubmitted())
		{
			if (!$form->isValid())
			{
				// TODO: implement form error

				$this->addFlash('error', (string)$form->getErrors(true, false));

				return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_add");
			}

			$this->postDecorateEntity($entity);

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
		}

		return $this->render($this->getAddTemplate(), array_merge([
			'form' => $form->createView(),
			'list' => [
				'type' => $this->getListType()
			]
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
	protected function doEditIndex(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		$form = $this->createForm($this->getFormClassName(), $entity);
		$form->handleRequest($request);

		if ($form->isSubmitted())
		{
			if (!$form->isValid())
			{
				// TODO: implement form error

				$this->addFlash('error', (string)$form->getErrors(true, false));

				return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_edit", [
					'id' => $entity->getId()
				]);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
		}

		return $this->render($this->getEditTemplate(), array_merge([
			'form' => $form->createView(),
			'entity' => $entity,
			'list' => [
				'type' => $this->getListType()
			]
		], $this->getEntityBehaviors($entity)));
	}

	/**
	 * Get entity behaviors
	 *
	 * @param object $entity
	 *
	 * @return array
	 */
	private function getEntityBehaviors($entity)
	{
		return [
			'isTranslatable' => $entity instanceof TranslatableInterface,
			'isPublishable' => $entity instanceof PublishableInterface,
			'isSoftDeletable' => $entity instanceof SoftDeletableInterface,
			'isViewable' => $entity instanceof ViewableInterface
		];
	}

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|Response
	 */
	protected function doDestroyIndex(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($entity);
		$em->flush();

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Get list template
	 *
	 * @return string
	 */
	protected function getListTemplate(): string
	{
		return '@SoftmediaAdmin/Form/list.html.twig';
	}

	/**
	 * Get add template
	 *
	 * @return string
	 */
	protected function getAddTemplate(): string
	{
		return '@SoftmediaAdmin/Form/add.html.twig';
	}

	/**
	 * Get edit template
	 *
	 * @return string
	 */
	protected function getEditTemplate(): string
	{
		return '@SoftmediaAdmin/Form/edit.html.twig';
	}

	/**
	 * Entity not found
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	protected function entityNotFound(int $id)
	{
		return new Response("Entity of type '{$this->getEntityClassName()}' with id '{$id}' not found", 404);
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
	abstract public function addIndex(Request $request);

	/**
	 * Edit entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function editIndex(Request $request, int $id);

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function destroyIndex(Request $request, int $id);
}