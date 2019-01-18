<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\Collection;
use Duo\CoreBundle\Entity\RevisionInterface as EntityRevisionInterface;

interface RevisionInterface
{
	/**
	 * Add revision
	 *
	 * @param EntityRevisionInterface $revision
	 *
	 * @return RevisionInterface
	 */
	public function addRevision(EntityRevisionInterface $revision): RevisionInterface;

	/**
	 * Remove revision
	 *
	 * @param EntityRevisionInterface $revision
	 *
	 * @return RevisionInterface
	 */
	public function removeRevision(EntityRevisionInterface $revision): RevisionInterface;

	/**
	 * Get revisions
	 *
	 * @return Collection
	 */
	public function getRevisions(): Collection;
}
