<?php

namespace Duo\BehaviorBundle\Event;

use Duo\BehaviorBundle\Entity\SoftDeleteInterface;
use Symfony\Component\EventDispatcher\Event;

class SoftDeleteEvent extends Event
{
	/**
	 * @var SoftDeleteInterface
	 */
	private $entity;

	/**
	 * SoftDeleteEvent constructor
	 *
	 * @param SoftDeleteInterface $entity
	 */
	public function __construct(SoftDeleteInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param SoftDeleteInterface $entity
	 *
	 * @return SoftDeleteEvent
	 */
	public function setEntity(SoftDeleteInterface $entity): SoftDeleteEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return SoftDeleteInterface
	 */
	public function getEntity(): ?SoftDeleteInterface
	{
		return $this->entity;
	}
}