<?php

namespace Duo\BehaviorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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
	public function getRevisions();
}