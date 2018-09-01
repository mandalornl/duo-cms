<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface RevisionInterface
{
	/**
	 * Set revision
	 *
	 * @param RevisionInterface $revision
	 *
	 * @return RevisionInterface
	 */
	public function setRevision(RevisionInterface $revision = null): RevisionInterface;

	/**
	 * Get revision
	 *
	 * @return RevisionInterface
	 */
	public function getRevision(): ?RevisionInterface;

	/**
	 * Add revision
	 *
	 * @param RevisionInterface $revision
	 *
	 * @return RevisionInterface
	 */
	public function addRevision(RevisionInterface $revision): RevisionInterface;

	/**
	 * Remove revision
	 *
	 * @param RevisionInterface $revision
	 *
	 * @return RevisionInterface
	 */
	public function removeRevision(RevisionInterface $revision): RevisionInterface;

	/**
	 * Get revisions
	 *
	 * @return ArrayCollection
	 */
	public function getRevisions(): Collection;
}