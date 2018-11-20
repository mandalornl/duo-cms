<?php

namespace Duo\AdminBundle\Controller\Listing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Action\ActionInterface;
use Duo\AdminBundle\Configuration\Field\FieldInterface;
use Duo\AdminBundle\Configuration\Filter\FilterInterface;
use Duo\AdminBundle\Configuration\Filter\SearchInterface;
use Duo\AdminBundle\Form\Listing\FilterType;
use Duo\AdminBundle\Form\Listing\SearchType;
use Duo\AdminBundle\Tools\ORM\Query;
use Duo\AdminBundle\Tools\Pagination\Paginator;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

abstract class AbstractIndexController extends AbstractController
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
	protected $actions;

	/**
	 * Index action
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	protected function doIndexAction(Request $request): Response
	{
		// define properties
		$this->defineFields($request);
		$this->defineFilters($request);
		$this->defineActions($request);

		return $this->render($this->getIndexTemplate(), (array)$this->getDefaultContext([
			'paginator' => $this->getPaginator($request),
			'list' => array_merge([
				'filterForm' => $this->getFilterFormView($request),
				'searchForm' => $this->getSearchFormView($request),
				'fields' => $this->getFields(),
				'sorting' => $this->getSorting($request),
				'actions' => $this->getActions(),
				'view' => $this->getView($request),
				'views' => [
					'list' => $this->getListViewTemplate(),
					'grid' => $this->getGridViewTemplate()
				]
			], $this->getListBehaviors())
		]));
	}

	/**
	 * Modal iframe action
	 *
	 * @Route("/modal/iframe", name="modal_iframe", methods={ "GET" })
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function modalIframeAction(Request $request): JsonResponse
	{
		return $this->json(
			$this->renderView('@DuoAdmin/Listing/Modal/iframe.html.twig', [
				'type' => $this->getType(),
				'routePrefix' => $this->getRoutePrefix()
			])
		);
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
	 * Get index template
	 *
	 * @return string
	 */
	protected function getIndexTemplate(): string
	{
		return '@DuoAdmin/Listing/index.html.twig';
	}

	/**
	 * Get list view template
	 *
	 * @return string
	 */
	protected function getListViewTemplate(): string
	{
		return '@DuoAdmin/Listing/View/list.html.twig';
	}

	/**
	 * Get list view template
	 *
	 * @return string
	 */
	protected function getGridViewTemplate(): string
	{
		return '@DuoAdmin/Listing/View/grid.html.twig';
	}

	/**
	 * Get view
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	protected function getView(Request $request): string
	{
		$session = $request->getSession();
		$sessionName = $this->getSessionName($request, 'view');

		// store view
		if ($request->query->has('view'))
		{
			$session->set($sessionName, $request->query->get('view'));
		}

		return $session->get($sessionName, $this->getDefaultView());
	}

	/**
	 * Get default view
	 *
	 * @return string
	 */
	protected function getDefaultView(): string
	{
		return 'list';
	}

	/**
	 * Get paginator
	 *
	 * @param Request $request
	 *
	 * @return Paginator
	 */
	protected function getPaginator(Request $request): Paginator
	{
		$limit = $this->getPaginatorLimit($request);
		$page = $this->getPaginatorPage($request);

		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClass());

		$builder = $repository->createQueryBuilder('e');

		// join translations
		if ($this->getEntityReflectionClass()->implementsInterface(TranslateInterface::class))
		{
			$builder
				->join('e.translations', 't', Join::WITH, 't.entity = e AND t.locale = :locale')
				->setParameter('locale', $request->getLocale());
		}

		// don't fetch deleted entities
		if ($this->getEntityReflectionClass()->implementsInterface(DeleteInterface::class))
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		// apply search or revert to filters
		if (!$this->applySearch($request, $builder))
		{
			// apply filters or revert to defaults
			if (!$this->applyFilters($request, $builder))
			{
				$this->defaultFilters($request, $builder);
			}
		}

		// apply sorting or revert to defaults
		if (!$this->applySorting($request, $builder))
		{
			$this->defaultSorting($request, $builder);
		}

		return (new Paginator($builder))
			->setPage($page)
			->setLimit($limit)
			->createView();
	}

	/**
	 * Get paginator limit
	 *
	 * @param Request $request
	 *
	 * @return int
	 */
	protected function getPaginatorLimit(Request $request): ?int
	{
		$session = $request->getSession();
		$sessionName = $this->getSessionName($request, 'limit');

		// store limit
		if ($request->query->has('limit'))
		{
			$session->set($sessionName, (int)$request->query->get('limit'));

			// clear page
			$session->remove($this->getSessionName($request, 'page'));
		}

		return $session->get($sessionName);
	}

	/**
	 * Get paginator page
	 *
	 * @param Request $request
	 *
	 * @return int
	 */
	protected function getPaginatorPage(Request $request): ?int
	{
		$session = $request->getSession();
		$sessionName = $this->getSessionName($request, 'page');

		// store page
		if ($request->query->has('page'))
		{
			$session->set($sessionName, (int)$request->query->get('page'));
		}

		return $session->get($sessionName);
	}

	/**
	 * Get list behaviors
	 *
	 * @return array
	 */
	protected function getListBehaviors(): array
	{
		return [
			'isSortable' => $this->isSortable(),
			'isDeletable' => $this->isDeletable(),
			'canSwitchView' => $this->canSwitchView()
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
		$this->getFields()->set($field->getHash(), $field);

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
		$this->getFields()->remove($field->getHash());

		return $this;
	}

	/**
	 * Get fields
	 *
	 * @return ArrayCollection
	 */
	public function getFields(): ArrayCollection
	{
		return $this->fields = $this->fields ?: new ArrayCollection();
	}

	/**
	 * Sorting action
	 *
	 * @Route(
	 *     path="/sorting/{sort}/{order}",
	 *     name="sorting",
	 *     requirements={ "order" = "asc|desc" },
	 *     defaults={ "order" = "asc" },
	 *     methods={ "GET", "POST" }
	 * )
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
		$sessionName = $this->getSessionName($request, 'sorting');

		$sortingData = $session->get($sessionName, []);
		$sortingData = array_merge($sortingData, [
			'sort' => $sort,
			'order' => $order
		]);

		$session->set($sessionName, $sortingData);

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index", $request->query->all());
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
		$sessionName = $this->getSessionName($request, 'sorting');

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
		$sessionName = $this->getSessionName($request, 'sorting');

		$sortingData = $session->get($sessionName, []);

		if (!count($sortingData))
		{
			return false;
		}

		/**
		 * @var FieldInterface $field
		 */
		$field = $this->getFields()->get($sortingData['sort']);

		if ($field === null)
		{
			return false;
		}

		$field->applySorting($builder, $sortingData['order']);

		return true;
	}

	/**
	 * Default sorting
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.id', 'ASC');
	}

	/**
	 * Define fields
	 *
	 * @param Request $request
	 */
	abstract protected function defineFields(Request $request): void;

	/**
	 * Add filter
	 *
	 * @param FilterInterface $filter
	 *
	 * @return $this
	 */
	public function addFilter(FilterInterface $filter)
	{
		$this->getFilters()->set($filter->getHash(), $filter);

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
		$this->getFilters()->remove($filter->getHash());

		return $this;
	}

	/**
	 * Get filters
	 *
	 * @return ArrayCollection
	 */
	public function getFilters(): ArrayCollection
	{
		return $this->filters = $this->filters ?: new ArrayCollection();
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
		$sessionName = $this->getSessionName($request, 'filter');

		// clear filter
		if ($request->query->has('clear'))
		{
			$session->remove($sessionName);

			$request->query->remove('clear');

			return $this->redirectToRoute($routeName, $request->query->all());
		}

		$filterData = $session->get($sessionName, []);

		/**
		 * @var FormInterface $form
		 */
		$form = $this->createForm(FilterType::class, $filterData, [
			'filters' => $this->getFilters()
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// remove empty filters
			$session->set($sessionName, array_filter($form->getData(), function(array $data)
			{
				return !in_array($data['value'], [null, []], true);
			}));

			// clear search
			$session->remove($this->getSessionName($request, 'search'));
		}

		return $this->redirectToRoute($routeName, $request->query->all());
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
		$sessionName = $this->getSessionName($request, 'filter');

		$filterData = $session->get($sessionName, []);

		/**
		 * @var FormInterface $form
		 */
		return $this->createForm(FilterType::class, $filterData, [
			'action' => $this->generateUrl("{$this->getRoutePrefix()}_filter", $request->query->all()),
			'filters' => $this->getFilters()
		]);
	}

	/**
	 * Get filter form view
	 *
	 * @param Request $request
	 *
	 * @return FormView
	 *
	 * @throws \Throwable
	 */
	protected function getFilterFormView(Request $request): ?FormView
	{
		if (($form = $this->getFilterForm($request)) === null)
		{
			return null;
		}

		return $form->createView();
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
		$sessionName = $this->getSessionName($request, 'filter');

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
			$filter->applyFilter($builder, $data);
		}

		// clear search and page
		$session->remove($this->getSessionName($request, 'search'));
		$session->remove($this->getSessionName($request, 'page'));

		return true;
	}

	/**
	 * Default filters
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 */
	protected function defaultFilters(Request $request, QueryBuilder $builder): void
	{
		// Implement applyDefaultFilters() method
	}

	/**
	 * Define filters
	 *
	 * @param Request $request
	 */
	abstract protected function defineFilters(Request $request): void;

	/**
	 * Search action
	 *
	 * @Route("/search", name="search", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function searchAction(Request $request): RedirectResponse
	{
		$routeName = "{$this->getRoutePrefix()}_index";

		$session = $request->getSession();
		$sessionName = $this->getSessionName($request, 'search');

		// clear search
		if ($request->query->has('clear'))
		{
			$session->remove($sessionName);

			$request->query->remove('clear');

			return $this->redirectToRoute($routeName, $request->query->all());
		}

		/**
		 * @var FormInterface $form
		 */
		$form = $this->createForm(SearchType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$session->set($sessionName, $form->getData()['q']);

			// clear filter(s)
			$session->remove($this->getSessionName($request, 'filter'));
		}

		return $this->redirectToRoute($routeName, $request->query->all());
	}

	/**
	 * Get search form
	 *
	 * @param Request $request
	 *
	 * @return FormInterface
	 *
	 * @throws \Throwable
	 */
	protected function getSearchForm(Request $request): ?FormInterface
	{
		$filters = $this->getFilters()->filter(function(FilterInterface $filter)
		{
			return $filter instanceof SearchInterface;
		});

		if (!count($filters))
		{
			return null;
		}

		$session = $request->getSession();
		$sessionName = $this->getSessionName($request, 'search');

		// store keyword
		if ($request->query->has('q'))
		{
			$session->set($sessionName, $request->query->get('q'));

			$request->query->remove('q');
		}

		return $this->createForm(SearchType::class, [
			'q' => $session->get($sessionName)
		], [
			'action' => $this->generateUrl("{$this->getRoutePrefix()}_search", $request->query->all())
		]);
	}

	/**
	 * Get search form view
	 *
	 * @param Request $request
	 *
	 * @return FormView
	 *
	 * @throws \Throwable
	 */
	protected function getSearchFormView(Request $request): ?FormView
	{
		if (($form = $this->getSearchForm($request)) === null)
		{
			return null;
		}

		return $form->createView();
	}

	/**
	 * Apply search
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 *
	 * @return bool
	 */
	protected function applySearch(Request $request, QueryBuilder $builder): bool
	{
		$session = $request->getSession();
		$sessionName = $this->getSessionName($request, 'search');

		if (!($keyword = $session->get($sessionName, $request->query->get('q'))))
		{
			return false;
		}

		/**
		 * @var SearchInterface[] $filters
		 */
		$filters = $this->getFilters()->filter(function(FilterInterface $filter)
		{
			return $filter instanceof SearchInterface;
		});

		if (!count($filters))
		{
			return false;
		}

		$orX = $builder->expr()->orX();

		foreach ($filters as $filter)
		{
			$filter->applySearch($builder, $orX, $keyword);
		}

		$builder
			->andWhere($orX)
			->setParameter('keyword', Query::escapeLike($keyword));

		// clear filter and page
		$session->remove($this->getSessionName($request, 'filter'));
		$session->remove($this->getSessionName($request, 'page'));

		return true;
	}

	/**
	 * Add action
	 *
	 * @param ActionInterface $action
	 *
	 * @return $this
	 */
	public function addAction(ActionInterface $action)
	{
		$this->getActions()->add($action);

		return $this;
	}

	/**
	 * Remove action
	 *
	 * @param ActionInterface $action
	 *
	 * @return $this
	 */
	public function removeAction(ActionInterface $action)
	{
		$this->getActions()->removeElement($action);

		return $this;
	}

	/**
	 * Get actions
	 *
	 * @return ArrayCollection
	 */
	public function getActions(): ArrayCollection
	{
		return $this->actions = $this->actions ?: new ArrayCollection();
	}

	/**
	 * Define actions
	 *
	 * @param Request $request
	 */
	protected function defineActions(Request $request): void
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
		return $this->getEntityReflectionClass()->implementsInterface(SortInterface::class) &&
			!$this->getEntityReflectionClass()->implementsInterface(TreeInterface::class);
	}

	/**
	 * Whether or not entities implement DeleteInterface
	 *
	 * @return bool
	 */
	protected function isDeletable(): bool
	{
		return $this->getEntityReflectionClass()->implementsInterface(DeleteInterface::class);
	}

	/**
	 * Whether or not view can be switched
	 *
	 * @return bool
	 */
	protected function canSwitchView(): bool
	{
		return false;
	}
}