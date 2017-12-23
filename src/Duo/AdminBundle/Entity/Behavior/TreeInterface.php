<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;

interface TreeInterface
{
	/**
	 * Set parent
	 *
	 * @param TreeInterface $parent
	 *
	 * @return TreeInterface
	 */
	public function setParent(TreeInterface $parent = null): TreeInterface;

	/**
	 * Get parent
	 *
	 * @return TreeInterface
	 */
	public function getParent(): ?TreeInterface;

	/**
	 * Add child
	 *
	 * @param TreeInterface $child
	 *
	 * @return TreeInterface
	 */
	public function addChild(TreeInterface $child): TreeInterface;

	/**
	 * Remove child
	 *
	 * @param TreeInterface $child
	 *
	 * @return TreeInterface
	 */
	public function removeChild(TreeInterface $child): TreeInterface;

	/**
	 * Get children
	 *
	 * @return ArrayCollection
	 */
	public function getChildren();
}