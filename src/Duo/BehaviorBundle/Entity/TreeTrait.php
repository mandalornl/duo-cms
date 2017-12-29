<?php

namespace Duo\BehaviorBundle\Entity;

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
	 * {@inheritdoc}
	 */
	public function setParent(TreeInterface $parent = null): TreeInterface
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): ?TreeInterface
	{
		return $this->parent;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addChild(TreeInterface $child): TreeInterface
	{
		$this->getChildren()->add($child);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeChild(TreeInterface $child): TreeInterface
	{
		$this->getChildren()->removeElement($child);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getChildren()
	{
		return $this->children = $this->children ?: new ArrayCollection();
	}
}