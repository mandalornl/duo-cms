<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;

interface CloneableInterface
{
	/**
	 * Set version
	 *
	 * @param CloneableInterface $version
	 *
	 * @return CloneableInterface
	 */
	public function setVersion(CloneableInterface $version = null): CloneableInterface;

	/**
	 * Get version
	 *
	 * @return CloneableInterface
	 */
	public function getVersion(): ?CloneableInterface;

	/**
	 * Add version
	 *
	 * @param CloneableInterface $version
	 *
	 * @return CloneableInterface
	 */
	public function addVersion(CloneableInterface $version): CloneableInterface;

	/**
	 * Remove version
	 *
	 * @param CloneableInterface $version
	 *
	 * @return CloneableInterface
	 */
	public function removeVersion(CloneableInterface $version): CloneableInterface;

	/**
	 * Get versions
	 *
	 * @return ArrayCollection
	 */
	public function getVersions();
}