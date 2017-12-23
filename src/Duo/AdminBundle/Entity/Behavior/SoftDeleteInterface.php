<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface SoftDeleteInterface
{
	/**
	 * Set deletedAt
	 *
	 * @param \DateTime $deletedAt
	 *
	 * @return SoftDeleteInterface
	 */
	public function setDeletedAt(\DateTime $deletedAt): SoftDeleteInterface;

	/**
	 * Get deletedAt
	 *
	 * @return \DateTime
	 */
	public function getDeletedAt(): ?\DateTime;

	/**
	 * Delete entity
	 *
	 * @return SoftDeleteInterface
	 */
	public function delete(): SoftDeleteInterface;

	/**
	 * Undelete entity
	 *
	 * @return SoftDeleteInterface
	 */
	public function undelete(): SoftDeleteInterface;

	/**
	 * Check whether or not entity is deleted
	 *
	 * @return bool
	 */
	public function isDeleted(): bool;
}