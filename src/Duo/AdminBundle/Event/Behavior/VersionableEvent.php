<?php

namespace Duo\AdminBundle\Event\Behavior;

use Duo\AdminBundle\Entity\Behavior\VersionableInterface;
use Symfony\Component\EventDispatcher\Event;

final class VersionableEvent extends Event
{
	/**
	 * @var VersionableInterface
	 */
	private $entity;

	/**
	 * @var VersionableInterface
	 */
	private $origin;

	/**
	 * VersionableEvent constructor
	 *
	 * @param VersionableInterface $entity
	 * @param VersionableInterface $origin
	 */
	public function __construct(VersionableInterface $entity, VersionableInterface $origin)
	{
		$this->entity = $entity;
		$this->origin = $origin;
	}

	/**
	 * Set entity
	 *
	 * @param VersionableInterface $entity
	 *
	 * @return VersionableEvent
	 */
	public function setEntity(VersionableInterface $entity): VersionableEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return VersionableInterface
	 */
	public function getEntity(): ?VersionableInterface
	{
		return $this->entity;
	}

	/**
	 * Set origin
	 *
	 * @param VersionableInterface $origin
	 *
	 * @return VersionableEvent
	 */
	public function setOrigin(VersionableInterface $origin): VersionableEvent
	{
		$this->origin = $origin;

		return $this;
	}

	/**
	 * Get origin
	 *
	 * @return VersionableInterface
	 */
	public function getOrigin(): ?VersionableInterface
	{
		return $this->origin;
	}
}