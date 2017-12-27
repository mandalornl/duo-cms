<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait SoftDeleteTrait
{
	/**
	 * @var \DateTime
	 * 
	 * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
	 */
	protected $deletedAt;

	/**
	 * @var UserInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\AdminBundle\Entity\Security\User")
	 * @ORM\JoinColumns({
	 *     @ORM\JoinColumn(name="deleted_by_id", referencedColumnName="id", onDelete="SET NULL")
	 * })
	 */
	protected $deletedBy;

	/**
	 * {@inheritdoc}
	 */
	public function setDeletedAt(\DateTime $deletedAt): SoftDeleteInterface
	{
		$this->deletedAt = $deletedAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDeletedAt(): ?\DateTime
	{
		return $this->deletedAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDeletedBy(UserInterface $deletedBy = null): SoftDeleteInterface
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
	public function delete(): SoftDeleteInterface
	{
		$this->deletedAt = new \DateTime();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function undelete(): SoftDeleteInterface
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
}