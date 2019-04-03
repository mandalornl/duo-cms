<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait LockTrait
{
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="locked", type="boolean", options={ "default" = 0 })
	 */
	protected $locked = false;

	/**
	 * {@inheritDoc}
	 */
	public function setLocked(bool $locked): LockInterface
	{
		$this->locked = $locked;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isLocked(): bool
	{
		return $this->locked;
	}
}
