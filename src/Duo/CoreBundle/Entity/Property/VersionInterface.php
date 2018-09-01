<?php

namespace Duo\CoreBundle\Entity\Property;

interface VersionInterface
{
	/**
	 * Set version
	 *
	 * @param int $version
	 *
	 * @return VersionInterface
	 */
	public function setVersion(int $version): VersionInterface;

	/**
	 * Get version
	 *
	 * @return int
	 */
	public function getVersion(): int;
}