<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Filter\FilterInterface;
use Duo\AdminBundle\Form\ListingFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait FilterTrait
{
	/**
	 * @var ArrayCollection
	 */
	protected $filters;

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
		return $this->filters = $this->filters ?: new ArrayCollection();
	}

	/**
	 * Filter action
	 *
	 * @Route("/filter", name="filter")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 * @return RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	public function filterAction(Request $request): RedirectResponse
	{
		/**
		 * @var AbstractListingController $this
		 */
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
	 * @throws AnnotationException
	 */
	protected function getFilterForm(Request $request): ?FormInterface
	{
		if (!count($this->getFilters()))
		{
			return null;
		}

		/**
		 * @var AbstractListingController $this
		 */
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
	 */
	protected function applyFilters(Request $request, QueryBuilder $builder)
	{
		/**
		 * @var AbstractListingController $this
		 */
		$session = $request->getSession();
		$sessionName = "filter_{$this->getType()}";

		$filterData = $session->get($sessionName, []);
		if (!count($filterData))
		{
			return;
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
	}

	/**
	 * Define filters
	 */
	abstract protected function defineFilters(): void;
}