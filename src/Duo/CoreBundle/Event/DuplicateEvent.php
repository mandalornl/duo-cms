<?php

namespace Duo\CoreBundle\Event;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Symfony\Component\EventDispatcher\Event;

class DuplicateEvent extends Event
{
	/**
	 * @var DuplicateInterface
	 */
	private $entity;

	/**
	 * DuplicateEvent constructor
	 *
	 * @param DuplicateInterface $entity
	 */
	public function __construct(DuplicateInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param DuplicateInterface $entity
	 *
	 * @return DuplicateEvent
	 */
	public function setEntity(DuplicateInterface $entity): DuplicateEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return DuplicateInterface
	 */
	public function getEntity(): ?DuplicateInterface
	{
		return $this->entity;
	}
}