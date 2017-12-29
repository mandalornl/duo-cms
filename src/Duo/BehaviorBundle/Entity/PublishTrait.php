<?php

namespace Duo\BehaviorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait PublishTrait
{
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="publish_at", type="datetime", nullable=true)
	 */
	protected $publishAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="unpublish_at", type="datetime", nullable=true)
	 */
	protected $unpublishAt;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\SecurityBundle\Entity\User")
	 * @ORM\JoinColumns({
	 *     @ORM\JoinColumn(name="published_by_id", referencedColumnName="id", onDelete="SET NULL")
	 * })
	 */
	protected $publishedBy;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\SecurityBundle\Entity\User")
	 * @ORM\JoinColumns({
	 *     @ORM\JoinColumn(name="unpublished_by_id", referencedColumnName="id", onDelete="SET NULL")
	 * })
	 */
	protected $unpublishedBy;

	/**
	 * {@inheritdoc}
	 */
	public function setPublishAt(\DateTime $publishAt = null): PublishInterface
	{
		$this->publishAt = $publishAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPublishAt(): ?\DateTime
	{
		return $this->publishAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setUnpublishAt(\DateTime $unpublishAt = null): PublishInterface
	{
		$this->unpublishAt = $unpublishAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUnpublishAt(): ?\DateTime
	{
		return $this->unpublishAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPublishedBy(UserInterface $publishedBy = null): PublishInterface
	{
		$this->publishedBy = $publishedBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPublishedBy(): ?UserInterface
	{
		return $this->publishedBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setUnpublishedBy(UserInterface $unpublishedBy = null): PublishInterface
	{
		$this->unpublishedBy = $unpublishedBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUnpublishedBy(): ?UserInterface
	{
		return $this->unpublishedBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public function publish(): PublishInterface
	{
		$this->publishAt = new \DateTime();

		return $this;
	}

	/**
	 * {@inheritdoc}
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
	 */
	public function isPublished(): bool
	{
		if ($this->publishAt === null && $this->unpublishAt === null)
		{
			return true;
		}

		$dateTime = new \DateTime();
		if ($this->publishAt !== null && $this->publishAt <= $dateTime &&
			$this->unpublishAt !== null && $this->unpublishAt > $dateTime)
		{
			return true;
		}

		if ($this->publishAt !== null && $this->publishAt <= $dateTime &&
			$this->unpublishAt === null)
		{
			return true;
		}

		return false;
	}
}