<?php

namespace Duo\CoreBundle\Event;

use Duo\CoreBundle\Entity\SortInterface;
use Symfony\Component\EventDispatcher\Event;

class SortEvent extends Event
{
	/**
	 * @var SortInterface
	 */
	private $entity;

	/**
	 * @var SortInterface
	 */
	private $origin;

	/**
	 * SortEvent constructor
	 *
	 * @param SortInterface $entity
	 * @param SortInterface $origin
	 */
	public function __construct(SortInterface $entity, SortInterface $origin)
	{
		$this->entity = $entity;
		$this->origin = $origin;
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

	/**
	 * Set origin
	 *
	 * @param SortInterface $origin
	 *
	 * @return SortEvent
	 */
	public function setOrigin(SortInterface $origin): SortEvent
	{
		$this->origin = $origin;

		return $this;
	}

	/**
	 * Get origin
	 *
	 * @return SortInterface
	 */
	public function getOrigin(): ?SortInterface
	{
		return $this->origin;
	}
}