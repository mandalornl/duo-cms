<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\FieldInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait FieldTrait
{
	/**
	 * @var ArrayCollection
	 */
	protected $fields;

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
		return $this->fields = $this->fields ?: new ArrayCollection();
	}

	/**
	 * Sorting action
	 *
	 * @Route("/sorting/{sort}/{order}", name="sorting", requirements={ "order" = "asc|desc" }, defaults={ "order" = "asc" })
	 * @Method({"GET", "POST"})
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
		/**
		 * @var AbstractListingController $this
		 */
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
		/**
		 * @var AbstractListingController $this
		 */
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
		/**
		 * @var AbstractListingController $this
		 */
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
	 * Define fields
	 */
	abstract protected function defineFields(): void;
}