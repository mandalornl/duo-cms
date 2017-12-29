<?php

namespace Duo\BehaviorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

interface VersionInterface
{
	/**
	 * Set version
	 *
	 * @param VersionInterface $version
	 *
	 * @return VersionInterface
	 */
	public function setVersion(VersionInterface $version = null): VersionInterface;

	/**
	 * Get version
	 *
	 * @return VersionInterface
	 */
	public function getVersion(): ?VersionInterface;

	/**
	 * Add version
	 *
	 * @param VersionInterface $version
	 *
	 * @return VersionInterface
	 */
	public function addVersion(VersionInterface $version): VersionInterface;

	/**
	 * Remove version
	 *
	 * @param VersionInterface $version
	 *
	 * @return VersionInterface
	 */
	public function removeVersion(VersionInterface $version): VersionInterface;

	/**
	 * Get versions
	 *
	 * @return ArrayCollection
	 */
	public function getVersions();
}