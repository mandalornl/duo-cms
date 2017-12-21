<?php

namespace Softmedia\AdminBundle\Event\Behavior;

use Softmedia\AdminBundle\Entity\Behavior\VersionableInterface;
use Symfony\Component\EventDispatcher\Event;

final class VersionableEvent extends Event
{
	/**
	 * @var VersionableInterface
	 */
	private $clone;

	/**
	 * @var VersionableInterface
	 */
	private $original;

	/**
	 * VersionableEvent constructor
	 *
	 * @param VersionableInterface $clone
	 * @param VersionableInterface $original
	 */
	public function __construct(VersionableInterface $clone, VersionableInterface $original)
	{
		$this->clone = $clone;
		$this->original = $original;
	}

	/**
	 * Set clone
	 *
	 * @param VersionableInterface $clone
	 *
	 * @return VersionableEvent
	 */
	public function setClone(VersionableInterface $clone): VersionableEvent
	{
		$this->clone = $clone;

		return $this;
	}

	/**
	 * Get clone
	 *
	 * @return VersionableInterface
	 */
	public function getClone(): ?VersionableInterface
	{
		return $this->clone;
	}

	/**
	 * Set original
	 *
	 * @param VersionableInterface $original
	 *
	 * @return VersionableEvent
	 */
	public function setOriginal(VersionableInterface $original): VersionableEvent
	{
		$this->original = $original;

		return $this;
	}

	/**
	 * Get original
	 *
	 * @return VersionableInterface
	 */
	public function getOriginal(): ?VersionableInterface
	{
		return $this->original;
	}
}