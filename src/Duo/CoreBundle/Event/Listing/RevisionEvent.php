<?php

namespace Duo\CoreBundle\Event\Listing;

use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Symfony\Component\EventDispatcher\Event;

class RevisionEvent extends Event
{
	/**
	 * @var RevisionInterface
	 */
	private $entity;

	/**
	 * @var RevisionInterface
	 */
	private $origin;

	/**
	 * RevisionEvent constructor
	 *
	 * @param RevisionInterface $entity
	 * @param RevisionInterface $origin
	 */
	public function __construct(RevisionInterface $entity, RevisionInterface $origin)
	{
		$this->entity = $entity;
		$this->origin = $origin;
	}

	/**
	 * Set entity
	 *
	 * @param RevisionInterface $entity
	 *
	 * @return RevisionEvent
	 */
	public function setEntity(RevisionInterface $entity): RevisionEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return RevisionInterface
	 */
	public function getEntity(): ?RevisionInterface
	{
		return $this->entity;
	}

	/**
	 * Set origin
	 *
	 * @param RevisionInterface $origin
	 *
	 * @return RevisionEvent
	 */
	public function setOrigin(RevisionInterface $origin): RevisionEvent
	{
		$this->origin = $origin;

		return $this;
	}

	/**
	 * Get origin
	 *
	 * @return RevisionInterface
	 */
	public function getOrigin(): ?RevisionInterface
	{
		return $this->origin;
	}
}