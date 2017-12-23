<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait SoftDeletableTrait
{
	/**
	 * @var \DateTime
	 * 
	 * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
	 */
	protected $deletedAt;

	/**
	 * {@inheritdoc}
	 */
	public function setDeletedAt(\DateTime $deletedAt): SoftDeletableInterface
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
	public function delete(): SoftDeletableInterface
	{
		$this->deletedAt = $this->getCurrentDateTime();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function undelete(): SoftDeletableInterface
	{
		$this->deletedAt = null;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isDeleted(): bool
	{
		return $this->deletedAt !== null && $this->deletedAt <= $this->getCurrentDateTime();
	}

	/**
	 * Get current date time
	 *
	 * @return \DateTime
	 */
	private function getCurrentDateTime(): \DateTime
	{
		$dateTime = new \DateTime();
		$dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		return $dateTime;
	}
}