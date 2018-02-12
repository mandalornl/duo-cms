<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\AdminBundle\Listing\Action\ItemActionInterface;

trait ItemActionTrait
{
	/**
	 * @var ArrayCollection
	 */
	protected $itemActions;

	/**
	 * Add item action
	 *
	 * @param ItemActionInterface $itemAction
	 *
	 * @return $this
	 */
	public function addItemAction(ItemActionInterface $itemAction)
	{
		$this->getItemActions()->add($itemAction);

		return $this;
	}

	/**
	 * Remove item action
	 *
	 * @param ItemActionInterface $itemAction
	 *
	 * @return $this
	 */
	public function removeItemAction(ItemActionInterface $itemAction)
	{
		$this->getItemActions()->removeElement($itemAction);

		return $this;
	}

	/**
	 * Get item actions
	 *
	 * @return ArrayCollection
	 */
	public function getItemActions(): ArrayCollection
	{
		return $this->itemActions = $this->itemActions ?: new ArrayCollection();
	}

	/**
	 * Define item actions
	 */
	protected function defineItemActions(): void
	{
		// Implement defineItemActions() method.
	}
}