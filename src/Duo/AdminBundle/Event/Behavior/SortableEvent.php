<?php

namespace Duo\AdminBundle\Event\Behavior;

use Duo\AdminBundle\Entity\Behavior\SortableInterface;
use Symfony\Component\EventDispatcher\Event;

class SortableEvent extends Event
{
	/**
	 * @var SortableInterface
	 */
	private $entity;

	/**
	 * SortableEvent constructor
	 *
	 * @param SortableInterface $entity
	 */
	public function __construct(SortableInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param SortableInterface $entity
	 *
	 * @return SortableEvent
	 */
	public function setEntity(SortableInterface $entity): SortableEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return SortableInterface
	 */
	public function getEntity(): ?SortableInterface
	{
		return $this->entity;
	}
}