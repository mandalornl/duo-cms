<?php

namespace Duo\CoreBundle\Entity\Property;

interface LockInterface
{
	/**
	 * Set locked
	 *
	 * @param bool $locked
	 *
	 * @return LockInterface
	 */
	public function setLocked(bool $locked): LockInterface;

	/**
	 * Is locked
	 *
	 * @return bool
	 */
	public function isLocked(): bool;
}
