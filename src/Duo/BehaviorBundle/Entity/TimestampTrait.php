<?php

namespace Duo\BehaviorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait TimestampTrait
{
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", options={ "default" = "CURRENT_TIMESTAMP" })
	 */
	protected $createdAt;

	/**
	 * @var \DateTime
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
	 * {@inheritdoc}
	 */
	public function setCreatedAt(\DateTime $createdAt = null): TimestampInterface
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCreatedAt(): ?\DateTime
	{
		return $this->createdAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setModifiedAt(\DateTime $modifiedAt = null): TimestampInterface
	{
		$this->modifiedAt = $modifiedAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getModifiedAt(): ?\DateTime
	{
		return $this->modifiedAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCreatedBy(UserInterface $createdBy = null): TimestampInterface
	{
		$this->createdBy = $createdBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCreatedBy(): ?UserInterface
	{
		return $this->createdBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setModifiedBy(UserInterface $modifiedBy = null): TimestampInterface
	{
		$this->modifiedBy = $modifiedBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
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