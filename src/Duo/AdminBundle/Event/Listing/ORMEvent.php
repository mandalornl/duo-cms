<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;

class ORMEvent extends Event
{
	/**
	 * @var mixed
	 */
	private $entity;

	/**
	 * ORMEvent constructor
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
	 * @return ORMEvent
	 */
	public function setEntity($entity): ORMEvent
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