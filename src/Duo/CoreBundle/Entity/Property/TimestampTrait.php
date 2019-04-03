<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait TimestampTrait
{
	/**
	 * @var \DateTimeInterface
	 *
	 * @ORM\Column(name="created_at", type="datetime", options={ "default" = "CURRENT_TIMESTAMP" })
	 */
	protected $createdAt;

	/**
	 * @var \DateTimeInterface
	 *
	 * @ORM\Column(name="modified_at", type="datetime", options={ "default" = "CURRENT_TIMESTAMP" })
	 */
	protected $modifiedAt;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\SecurityBundle\Entity\UserInterface")
	 * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $createdBy;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\SecurityBundle\Entity\UserInterface")
	 * @ORM\JoinColumn(name="modified_by_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $modifiedBy;

	/**
	 * {@inheritDoc}
	 */
	public function setCreatedAt(?\DateTimeInterface $createdAt): TimestampInterface
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->createdAt;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setModifiedAt(?\DateTimeInterface $modifiedAt): TimestampInterface
	{
		$this->modifiedAt = $modifiedAt;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getModifiedAt(): ?\DateTimeInterface
	{
		return $this->modifiedAt;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setCreatedBy(?UserInterface $createdBy): TimestampInterface
	{
		$this->createdBy = $createdBy;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCreatedBy(): ?UserInterface
	{
		return $this->createdBy;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setModifiedBy(?UserInterface $modifiedBy): TimestampInterface
	{
		$this->modifiedBy = $modifiedBy;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getModifiedBy(): ?UserInterface
	{
		return $this->modifiedBy;
	}

	/**
	 * On clone timestamps
	 */
	protected function onCloneTimestamps(): void
	{
		$this->createdAt = null;
		$this->modifiedAt = null;
		$this->createdBy = null;
		$this->modifiedBy = null;
	}
}
