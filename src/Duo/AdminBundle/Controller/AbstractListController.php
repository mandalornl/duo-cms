<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Action\ListActionInterface;
use Duo\AdminBundle\Configuration\Field\FieldInterface;
use Duo\AdminBundle\Configuration\Filter\FilterInterface;
use Duo\AdminBundle\Form\ListingFilterType;
use Duo\AdminBundle\Helper\PaginatorHelper;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\RevisionInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TimestampInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

abstract class AbstractListController extends AbstractController
{
	/**
	 * @var ArrayCollection
	 */
	protected $fields;

	/**
	 * @var ArrayCollection
	 */
	protected $filters;

	/**
	 * @var ArrayCollection
	 */
	protected $listActions;

	/**
	 * AbstractIndexController constructor
	 */
	public function __construct()
	{
		$this->fields = new ArrayCollection();
		$this->filters = new ArrayCollection();
		$this->listActions = new ArrayCollection();

		$this->defineFields();
		$this->defineFilters();
		$this->defineListActions();
	}

	/**
	 * Index view
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	protected function doIndexAction(Request $request): Response
	{
		return $this->render($this->getListTemplate(), (array)$this->getDefaultContext([
			'paginator' => $this->getPaginator($request),
			'list' => array_merge([
				'filterForm' => call_user_func(function() use ($request)
				{
					if (($form = $this->getFilterForm($request)) === null)
					{
						return null;
					}

					return $form->createView();
				}),
				'fields' => $this->fields,
				'sorting' => $this->getSorting($request),
				'actions' => $this->getListActions()
			], $this->getListBehaviors())
		]));
	}

	/**
	 * Index view
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	abstract public function indexAction(Request $request): Response;

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
	 * Get paginator
	 *
	 * @param Request $request
	 *
	 * @return PaginatorHelper
	 *
	 * @throws \Throwable
	 */
	protected function getPaginator(Request $request): PaginatorHelper
	{
		$reflectionClass = new \ReflectionClass($this->getEntityClass());

		$page = (int)$request->get('page') ?: null;
		$limit = (int)$request->get('limit') ?: null;

		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClass());

		$builder = $repository->createQueryBuilder('e');

		// join translations
		if ($reflectionClass->implementsInterface(TranslateInterface::class))
		{
			$builder
				->join('e.translations', 't', Join::WITH, 't.translatable = e AND t.locale = :locale')
				->setParameter('locale', $request->getLocale());
		}

		// only fetch latest revision of entities
		if ($reflectionClass->implementsInterface(RevisionInterface::class))
		{
			$builder->andWhere('e.revision = e.id');
		}

		// don't fetch deleted entities
		if ($reflectionClass->implementsInterface(DeleteInterface::class))
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		// apply filters or revert to defaults
		if (!$this->applyFilters($request, $builder))
		{
			$this->applyDefaultSorting($builder, $reflectionClass);
		}

		// apply sorting or revert to defaults
		if (!$this->applySorting($request, $builder))
		{
			$this->applyDefaultSorting($builder, $reflectionClass);
		}

		return (new PaginatorHelper($builder))
			->setPage($page)
			->setLimit($limit)
			->createView();
	}

	/**
	 * Get list behaviors
	 *
	 * @return array
	 *
	 * @throws \Throwable
	 */
	private function getListBehaviors()
	{
		return [
			'isSortable' => $this->isSortable(),
			'isDeletable' => $this->isDeletable()
		];
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
		$this->getFields()->set($field->getProperty(), $field);

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
		$this->getFields()->removeElement($field);

		return $this;
	}

	/**
	 * Get fields
	 *
	 * @return ArrayCollection
	 */
	public function getFields(): ArrayCollection
	{
		return $this->fields;
	}

	/**
	 * Sorting action
	 *
	 * @Route("/sorting/{sort}/{order}", name="sorting", requirements={ "order" = "asc|desc" }, defaults={ "order" = "asc" }, methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 * @param string $sort [optional]
	 * @param string $order [optional]
	 *
	 * @return RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function sortingAction(Request $request, string $sort, string $order = 'asc'): RedirectResponse
	{
		$session = $request->getSession();
		$sessionName = "sorting_{$this->getType()}";

		$sortingData = $session->get($sessionName, []);
		$sortingData = array_merge($sortingData, [
			'sort' => $sort,
			'order' => $order
		]);

		$session->set($sessionName, $sortingData);

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Get sorting
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function getSorting(Request $request): ?array
	{
		$session = $request->getSession();
		$sessionName = "sorting_{$this->getType()}";

		return $session->get($sessionName);
	}

	/**
	 * Apply sorting
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 *
	 * @return bool
	 */
	protected function applySorting(Request $request, QueryBuilder $builder): bool
	{
		$session = $request->getSession();
		$sessionName = "sorting_{$this->getType()}";

		$sortingData = $session->get($sessionName, []);
		if (!count($sortingData))
		{
			return false;
		}

		/**
		 * @var FieldInterface $field
		 */
		$field = $this->getFields()->get($sortingData['sort']);

		$builder->orderBy("{$field->getAlias()}.{$sortingData['sort']}", $sortingData['order']);

		return true;
	}

	/**
	 * Apply default sorting
	 *
	 * @param QueryBuilder $builder
	 * @param \ReflectionClass $reflectionClass
	 */
	protected function applyDefaultSorting(QueryBuilder $builder, \ReflectionClass $reflectionClass): void
	{
		// order by last modified
		if ($reflectionClass->implementsInterface(TimestampInterface::class))
		{
			$builder->addOrderBy('e.modifiedAt', 'DESC');
		}

		// order by weight
		if ($reflectionClass->implementsInterface(SortInterface::class))
		{
			$builder->addOrderBy('e.weight', 'ASC');
		}

		$builder->addOrderBy('e.id', 'ASC');
	}

	/**
	 * Define fields
	 */
	abstract protected function defineFields(): void;

	/**
	 * Add filter
	 *
	 * @param FilterInterface $filter
	 *
	 * @return $this
	 */
	public function addFilter(FilterInterface $filter)
	{
		$this->getFilters()->set($filter->getProperty(), $filter);

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
		$this->getFilters()->removeElement($filter);

		return $this;
	}

	/**
	 * Get filters
	 *
	 * @return ArrayCollection
	 */
	public function getFilters(): ArrayCollection
	{
		return $this->filters;
	}

	/**
	 * Filter action
	 *
	 * @Route("/filter", name="filter", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function filterAction(Request $request): RedirectResponse
	{
		$routeName = "{$this->getRoutePrefix()}_index";

		$session = $request->getSession();
		$sessionName = "filter_{$this->getType()}";

		// clear filter
		if ($request->query->has('clear'))
		{
			$session->remove($sessionName);

			return $this->redirectToRoute($routeName);
		}

		$filterData = $session->get($sessionName, []);

		/**
		 * @var FormInterface $form
		 */
		$form = $this->createForm(ListingFilterType::class, $filterData, [
			'filters' => $this->getFilters()
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$session->set($sessionName, $form->getData());
		}

		return $this->redirectToRoute($routeName);
	}

	/**
	 * Get filter form
	 *
	 * @param Request $request
	 *
	 * @return FormInterface
	 *
	 * @throws \Throwable
	 */
	protected function getFilterForm(Request $request): ?FormInterface
	{
		if (!count($this->getFilters()))
		{
			return null;
		}

		$session = $request->getSession();
		$sessionName = "filter_{$this->getType()}";

		$filterData = $session->get($sessionName, []);

		/**
		 * @var FormInterface $form
		 */
		return $this->createForm(ListingFilterType::class, $filterData, [
			'action' => $this->generateUrl("{$this->getRoutePrefix()}_filter"),
			'filters' => $this->getFilters()
		]);
	}

	/**
	 * Apply filters
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 *
	 * @return bool
	 */
	protected function applyFilters(Request $request, QueryBuilder $builder): bool
	{
		$session = $request->getSession();
		$sessionName = "filter_{$this->getType()}";

		$filterData = $session->get($sessionName, []);
		if (!count($filterData))
		{
			return false;
		}

		foreach ($filterData as $key => $data)
		{
			if (!$this->getFilters()->containsKey($key))
			{
				continue;
			}

			/**
			 * @var FilterInterface $filter
			 */
			$filter = $this->getFilters()->get($key);
			$filter
				->setQueryBuilder($builder)
				->setData($data)
				->apply();
		}

		return true;
	}

	/**
	 * Apply default filters
	 *
	 * @param QueryBuilder $builder
	 * @param \ReflectionClass $reflectionClass
	 */
	protected function applyDefaultFilters(QueryBuilder $builder, \ReflectionClass $reflectionClass): void
	{

	}

	/**
	 * Define filters
	 */
	abstract protected function defineFilters(): void;

	/**
	 * Add list action
	 *
	 * @param ListActionInterface $listAction
	 *
	 * @return $this
	 */
	public function addListAction(ListActionInterface $listAction)
	{
		$this->getListActions()->add($listAction);

		return $this;
	}

	/**
	 * Remove list action
	 *
	 * @param ListActionInterface $listAction
	 *
	 * @return $this
	 */
	public function removeListAction(ListActionInterface $listAction)
	{
		$this->getListActions()->removeElement($listAction);

		return $this;
	}

	/**
	 * Get list actions
	 *
	 * @return ArrayCollection
	 */
	public function getListActions(): ArrayCollection
	{
		return $this->listActions;
	}

	/**
	 * Define list actions
	 */
	protected function defineListActions(): void
	{
		// Implement defineListActions() method.
	}

	/**
	 * Whether or not entities implement SortInterface
	 *
	 * @return bool
	 */
	protected function isSortable(): bool
	{
		return false;
	}

	/**
	 * Whether or not entities implement DeleteInterface
	 *
	 * @return bool
	 */
	protected function isDeletable(): bool
	{
		return false;
	}
}