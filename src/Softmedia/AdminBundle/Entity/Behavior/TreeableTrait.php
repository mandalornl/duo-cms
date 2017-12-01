<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait TreeableTrait
{
	/**
	 * @var TreeableInterface
	 */
	protected $parent;

	/**
	 * @var Collection
	 */
	protected $children;

	/**
	 * {@inheritdoc}
	 */
	public function setParent(TreeableInterface $parent = null): TreeableInterface
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): ?TreeableInterface
	{
		return $this->parent;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addChild(TreeableInterface $child): TreeableInterface
	{
		$this->getChildren()->add($child);

		return $this;
	}

	public function removeChild(TreeableInterface $child): TreeableInterface
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