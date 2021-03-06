<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface TreeInterface
{
	/**
	 * Set parent
	 *
	 * @param TreeInterface $parent
	 *
	 * @return TreeInterface
	 */
	public function setParent(?TreeInterface $parent): TreeInterface;

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
	public function getChildren(): Collection;
}