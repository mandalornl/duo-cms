<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait DeleteTrait
{
	/**
	 * @var \DateTimeInterface
	 *
	 * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
	 */
	protected $deletedAt;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\SecurityBundle\Entity\UserInterface")
	 * @ORM\JoinColumn(name="deleted_by_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $deletedBy;

	/**
	 * {@inheritdoc}
	 */
	public function setDeletedAt(\DateTimeInterface $deletedAt): DeleteInterface
	{
		$this->deletedAt = $deletedAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDeletedAt(): ?\DateTimeInterface
	{
		return $this->deletedAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDeletedBy(?UserInterface $deletedBy): DeleteInterface
	{
		$this->deletedBy = $deletedBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDeletedBy(): ?UserInterface
	{
		return $this->deletedBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(): DeleteInterface
	{
		$this->deletedAt = new \DateTime();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function undelete(): DeleteInterface
	{
		$this->deletedAt = null;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isDeleted(): bool
	{
		return $this->deletedAt !== null && $this->deletedAt <= new \DateTime();
	}

	/**
	 * On clone delete
	 */
	protected function onCloneDelete(): void
	{
		$this->deletedAt = null;
		$this->deletedBy = null;
	}
}
