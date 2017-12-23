<?php

namespace Duo\AdminBundle\Event\Behavior;

use Duo\AdminBundle\Entity\Behavior\VersionInterface;
use Symfony\Component\EventDispatcher\Event;

final class VersionEvent extends Event
{
	/**
	 * @var VersionInterface
	 */
	private $entity;

	/**
	 * @var VersionInterface
	 */
	private $origin;

	/**
	 * VersionEvent constructor
	 *
	 * @param VersionInterface $entity
	 * @param VersionInterface $origin
	 */
	public function __construct(VersionInterface $entity, VersionInterface $origin)
	{
		$this->entity = $entity;
		$this->origin = $origin;
	}

	/**
	 * Set entity
	 *
	 * @param VersionInterface $entity
	 *
	 * @return VersionEvent
	 */
	public function setEntity(VersionInterface $entity): VersionEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return VersionInterface
	 */
	public function getEntity(): ?VersionInterface
	{
		return $this->entity;
	}

	/**
	 * Set origin
	 *
	 * @param VersionInterface $origin
	 *
	 * @return VersionEvent
	 */
	public function setOrigin(VersionInterface $origin): VersionEvent
	{
		$this->origin = $origin;

		return $this;
	}

	/**
	 * Get origin
	 *
	 * @return VersionInterface
	 */
	public function getOrigin(): ?VersionInterface
	{
		return $this->origin;
	}
}