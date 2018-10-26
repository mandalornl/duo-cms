<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\Collection;
use Duo\CoreBundle\Entity\DraftInterface as EntityDraftInterface;

interface DraftInterface
{
	/**
	 * Add draft
	 *
	 * @param EntityDraftInterface $draft
	 *
	 * @return DraftInterface
	 */
	public function addDraft(EntityDraftInterface $draft): DraftInterface;

	/**
	 * Remove draft
	 *
	 * @param EntityDraftInterface $draft
	 *
	 * @return DraftInterface
	 */
	public function removeDraft(EntityDraftInterface $draft): DraftInterface;

	/**
	 * Get drafts
	 *
	 * @return Collection
	 */
	public function getDrafts(): Collection;
}