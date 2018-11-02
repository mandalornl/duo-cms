<?php

namespace Duo\CoreBundle\Event\Listing;

use Duo\CoreBundle\Entity\Property\SortInterface;
use Symfony\Component\EventDispatcher\Event;

class SortEvent extends Event
{
	/**
	 * @var SortInterface
	 */
	private $entity;

	/**
	 * SortEvent constructor
	 *
	 * @param SortInterface $entity
	 */
	public function __construct(SortInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param SortInterface $entity
	 *
	 * @return SortEvent
	 */
	public function setEntity(SortInterface $entity): SortEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return SortInterface
	 */
	public function getEntity(): ?SortInterface
	{
		return $this->entity;
	}
}