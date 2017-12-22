<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;

interface TreeableInterface
{
	/**
	 * Set parent
	 *
	 * @param TreeableInterface $parent
	 *
	 * @return TreeableInterface
	 */
	public function setParent(TreeableInterface $parent = null): TreeableInterface;

	/**
	 * Get parent
	 *
	 * @return TreeableInterface
	 */
	public function getParent(): ?TreeableInterface;

	/**
	 * Add child
	 *
	 * @param TreeableInterface $child
	 *
	 * @return TreeableInterface
	 */
	public function addChild(TreeableInterface $child): TreeableInterface;

	/**
	 * Remove child
	 *
	 * @param TreeableInterface $child
	 *
	 * @return TreeableInterface
	 */
	public function removeChild(TreeableInterface $child): TreeableInterface;

	/**
	 * Get children
	 *
	 * @return ArrayCollection
	 */
	public function getChildren();
}