<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;

class EntityEvent extends Event
{
	/**
	 * @var object
	 */
	private $entity;

	/**
	 * EntityEvent constructor
	 *
	 * @param object $entity
	 */
	public function __construct(object $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param object $entity
	 *
	 * @return EntityEvent
	 */
	public function setEntity(?object $entity): EntityEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return object
	 */
	public function getEntity(): ?object
	{
		return $this->entity;
	}
}