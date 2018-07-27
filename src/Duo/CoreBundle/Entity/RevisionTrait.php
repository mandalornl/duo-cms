<?php

namespace Duo\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait RevisionTrait
{
	/**
	 * @var RevisionInterface
	 */
	protected $revision;

	/**
	 * @var Collection
	 */
	protected $revisions;

	/**
	 * {@inheritdoc}
	 */
	public function setRevision(RevisionInterface $revision = null): RevisionInterface
	{
		$this->revision = $revision;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRevision(): ?RevisionInterface
	{
		return $this->revision;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addRevision(RevisionInterface $revision): RevisionInterface
	{
		$this->getRevisions()->add($revision);

		return $this;
	}

	/**
	 * {@inheritdoc
	 */
	public function removeRevision(RevisionInterface $revision): RevisionInterface
	{
		$this->getRevisions()->removeElement($revision);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRevisions(): Collection
	{
		return $this->revisions = $this->revisions ?: new ArrayCollection();
	}
}