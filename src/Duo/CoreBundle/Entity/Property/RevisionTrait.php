<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\CoreBundle\Entity\Property\RevisionInterface as PropertyRevisionInterface;
use Duo\CoreBundle\Entity\RevisionInterface as EntityRevisionInterface;

trait RevisionTrait
{
	/**
	 * @var Collection
	 */
	protected $revisions;

	/**
	 * {@inheritDoc}
	 */
	public function addRevision(EntityRevisionInterface $revision): PropertyRevisionInterface
	{
		$revision->setEntity($this);

		$this->getRevisions()->add($revision);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeRevision(EntityRevisionInterface $revision): PropertyRevisionInterface
	{
		$this->getRevisions()->removeElement($revision);

		$revision->setEntity(null);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRevisions(): Collection
	{
		return $this->revisions = $this->revisions ?: new ArrayCollection();
	}
}
