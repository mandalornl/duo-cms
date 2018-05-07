<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;

class ORMEvent extends Event
{
	/**
	 * @var object
	 */
	private $entity;

	/**
	 * ORMEvent constructor
	 *
	 * @param object $entity
	 */
	public function __construct($entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param object $entity
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
	 * @return object
	 */
	public function getEntity()
	{
		return $this->entity;
	}
}