<?php

namespace Duo\CoreBundle\Event;

use Duo\CoreBundle\Entity\Property\PublishInterface;
use Symfony\Component\EventDispatcher\Event;

class PublishEvent extends Event
{
	/**
	 * @var PublishInterface
	 */
	private $entity;

	/**
	 * PublishEvent constructor
	 *
	 * @param PublishInterface $entity
	 */
	public function __construct(PublishInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param PublishInterface $entity
	 *
	 * @return PublishEvent
	 */
	public function setEntity(PublishInterface $entity): PublishEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return PublishInterface
	 */
	public function getEntity(): ?PublishInterface
	{
		return $this->entity;
	}
}