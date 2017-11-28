<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

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
	 * Set deletedAt
	 *
	 * @param \DateTime $deletedAt
	 *
	 * @return $this
	 */
	public function setDeletedAt(\DateTime $deletedAt)
	{
		$this->deletedAt = $deletedAt;

		return $this;
	}

	/**
	 * Get deletedAt
	 *
	 * @return \DateTime
	 */
	public function getDeletedAt(): ?\DateTime
	{
		return $this->deletedAt;
	}

	/**
	 * Delete entity
	 *
	 * @return $this
	 */
	public function delete()
	{
		$this->deletedAt = $this->getCurrentDateTime();

		return $this;
	}

	/**
	 * Restore entity
	 *
	 * @return $this
	 */
	public function restore()
	{
		$this->deletedAt = null;

		return $this;
	}

	/**
	 * Check whether or not entity is deleted
	 *
	 * @return bool
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