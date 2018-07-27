<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;

class EntityEvent extends Event
{
	/**
	 * @var mixed
	 */
	private $entity;

	/**
	 * EntityEvent constructor
	 *
	 * @param mixed $entity
	 */
	public function __construct($entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param mixed $entity
	 *
	 * @return EntityEvent
	 */
	public function setEntity($entity): EntityEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return mixed
	 */
	public function getEntity()
	{
		return $this->entity;
	}
}