<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\HttpFoundation\Request;

class EntityEvent extends AbstractEvent
{
	/**
	 * @var object
	 */
	private $entity;

	/**
	 * EntityEvent constructor
	 *
	 * @param object $entity
	 * @param Request $request
	 */
	public function __construct(object $entity, Request $request)
	{
		$this->entity = $entity;

		parent::__construct($request);
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
