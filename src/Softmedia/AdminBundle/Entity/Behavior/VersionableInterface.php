<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;

interface VersionableInterface
{
	/**
	 * Set version
	 *
	 * @param VersionableInterface $version
	 *
	 * @return VersionableInterface
	 */
	public function setVersion(VersionableInterface $version = null): VersionableInterface;

	/**
	 * Get version
	 *
	 * @return VersionableInterface
	 */
	public function getVersion(): ?VersionableInterface;

	/**
	 * Add version
	 *
	 * @param VersionableInterface $version
	 *
	 * @return VersionableInterface
	 */
	public function addVersion(VersionableInterface $version): VersionableInterface;

	/**
	 * Remove version
	 *
	 * @param VersionableInterface $version
	 *
	 * @return VersionableInterface
	 */
	public function removeVersion(VersionableInterface $version): VersionableInterface;

	/**
	 * Get versions
	 *
	 * @return ArrayCollection
	 */
	public function getVersions();
}