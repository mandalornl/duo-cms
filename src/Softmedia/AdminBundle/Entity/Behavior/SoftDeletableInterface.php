<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

interface SoftDeletableInterface
{
	/**
	 * Set deletedAt
	 *
	 * @param \DateTime $deletedAt
	 *
	 * @return SoftDeletableInterface
	 */
	public function setDeletedAt(\DateTime $deletedAt): SoftDeletableInterface;

	/**
	 * Get deletedAt
	 *
	 * @return \DateTime
	 */
	public function getDeletedAt(): ?\DateTime;

	/**
	 * Delete entity
	 *
	 * @return SoftDeletableInterface
	 */
	public function delete(): SoftDeletableInterface;

	/**
	 * Restore entity
	 *
	 * @return SoftDeletableInterface
	 */
	public function restore(): SoftDeletableInterface;

	/**
	 * Check whether or not entity is deleted
	 *
	 * @return bool
	 */
	public function isDeleted(): bool;
}