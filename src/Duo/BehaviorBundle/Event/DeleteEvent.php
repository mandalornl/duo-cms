<?php

namespace Duo\BehaviorBundle\Event;

use Duo\BehaviorBundle\Entity\DeleteInterface;
use Symfony\Component\EventDispatcher\Event;

class DeleteEvent extends Event
{
	/**
	 * @var DeleteInterface
	 */
	private $entity;

	/**
	 * DeleteEvent constructor
	 *
	 * @param DeleteInterface $entity
	 */
	public function __construct(DeleteInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param DeleteInterface $entity
	 *
	 * @return DeleteEvent
	 */
	public function setEntity(DeleteInterface $entity): DeleteEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return DeleteInterface
	 */
	public function getEntity(): ?DeleteInterface
	{
		return $this->entity;
	}
}