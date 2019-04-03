<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait TreeTrait
{
	/**
	 * @var TreeInterface
	 */
	protected $parent;

	/**
	 * @var Collection
	 */
	protected $children;

	/**
	 * {@inheritDoc}
	 */
	public function setParent(?TreeInterface $parent): TreeInterface
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): ?TreeInterface
	{
		return $this->parent;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addChild(TreeInterface $child): TreeInterface
	{
		$child->setParent($this);

		$this->getChildren()->add($child);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeChild(TreeInterface $child): TreeInterface
	{
		$this->getChildren()->removeElement($child);

		$child->setParent(null);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getChildren(): Collection
	{
		return $this->children = $this->children ?: new ArrayCollection();
	}
}
