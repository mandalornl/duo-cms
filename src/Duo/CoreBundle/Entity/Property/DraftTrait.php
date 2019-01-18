<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\CoreBundle\Entity\DraftInterface as EntityDraftInterface;
use Duo\CoreBundle\Entity\Property\DraftInterface as PropertyDraftInterface;

trait DraftTrait
{
	/**
	 * @var Collection
	 */
	protected $drafts;

	/**
	 * {@inheritdoc}
	 */
	public function addDraft(EntityDraftInterface $draft): PropertyDraftInterface
	{
		$draft->setEntity($this);

		$this->getDrafts()->add($draft);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeDraft(EntityDraftInterface $draft): PropertyDraftInterface
	{
		$this->getDrafts()->removeElement($draft);

		$draft->setEntity(null);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDrafts(): Collection
	{
		return $this->drafts = $this->drafts ?: new ArrayCollection();
	}
}
