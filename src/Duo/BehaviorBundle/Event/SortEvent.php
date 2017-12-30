<?php

namespace Duo\BehaviorBundle\Event;

use Duo\BehaviorBundle\Entity\SortInterface;
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
	private $adjacentEntity;

	/**
	 * SortEvent constructor
	 *
	 * @param SortInterface $entity
	 * @param SortInterface $adjacentEntity [optional]
	 */
	public function __construct(SortInterface $entity, SortInterface $adjacentEntity = null)
	{
		$this->entity = $entity;
		$this->adjacentEntity = $adjacentEntity;
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
	 * Set adjacentEntity
	 *
	 * @param SortInterface $adjacentEntity
	 *
	 * @return SortEvent
	 */
	public function setAdjacentEntity(SortInterface $adjacentEntity): SortEvent
	{
		$this->adjacentEntity = $adjacentEntity;

		return $this;
	}

	/**
	 * Get adjacentEntity
	 *
	 * @return SortInterface
	 */
	public function getAdjacentEntity(): ?SortInterface
	{
		return $this->adjacentEntity;
	}
}