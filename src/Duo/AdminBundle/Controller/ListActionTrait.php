<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\AdminBundle\Listing\Action\ListActionInterface;

trait ListActionTrait
{
	/**
	 * @var ArrayCollection
	 */
	protected $listActions;

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
		return $this->listActions = $this->listActions ?: new ArrayCollection();
	}

	/**
	 * Define list actions
	 */
	protected function defineListActions(): void
	{
		// Implement defineListActions() method.
	}
}