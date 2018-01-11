<?php

namespace Duo\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ListingEvent extends Event
{
	/**
	 * @var object
	 */
	private $entity;

	/**
	 * ListingEvent constructor
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
	 * @return ListingEvent
	 */
	public function setEntity($entity): ListingEvent
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