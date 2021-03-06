<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait PublishTrait
{
	/**
	 * @var \DateTimeInterface
	 *
	 * @ORM\Column(name="publish_at", type="datetime", nullable=true)
	 */
	protected $publishAt;

	/**
	 * @var \DateTimeInterface
	 *
	 * @ORM\Column(name="unpublish_at", type="datetime", nullable=true)
	 */
	protected $unpublishAt;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\SecurityBundle\Entity\UserInterface")
	 * @ORM\JoinColumn(name="published_by_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $publishedBy;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\SecurityBundle\Entity\UserInterface")
	 * @ORM\JoinColumn(name="unpublished_by_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $unpublishedBy;

	/**
	 * {@inheritDoc}
	 */
	public function setPublishAt(?\DateTimeInterface $publishAt): PublishInterface
	{
		$this->publishAt = $publishAt;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPublishAt(): ?\DateTimeInterface
	{
		return $this->publishAt;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setUnpublishAt(?\DateTimeInterface $unpublishAt): PublishInterface
	{
		$this->unpublishAt = $unpublishAt;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUnpublishAt(): ?\DateTimeInterface
	{
		return $this->unpublishAt;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setPublishedBy(?UserInterface $publishedBy): PublishInterface
	{
		$this->publishedBy = $publishedBy;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPublishedBy(): ?UserInterface
	{
		return $this->publishedBy;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setUnpublishedBy(?UserInterface $unpublishedBy): PublishInterface
	{
		$this->unpublishedBy = $unpublishedBy;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUnpublishedBy(): ?UserInterface
	{
		return $this->unpublishedBy;
	}

	/**
	 * {@inheritDoc}
	 */
	public function publish(): PublishInterface
	{
		$this->publishAt = new \DateTime();
		$this->unpublishAt = null;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function unpublish(): PublishInterface
	{
		$this->unpublishAt = new \DateTime();

		return $this;
	}

	/**
	 * Is published
	 *
	 * @return bool
	 *
	 * @throws \Throwable
	 */
	public function isPublished(): bool
	{
		$now = new \DateTime();

		return $this->publishAt !== null && $this->publishAt <= $now &&
			($this->unpublishAt === null || $this->unpublishAt > $now);
	}

	/**
	 * On clone publish
	 */
	protected function onClonePublish(): void
	{
		$this->publishAt = null;
		$this->publishedBy = null;
		$this->unpublishAt = null;
		$this->unpublishedBy = null;
	}
}
