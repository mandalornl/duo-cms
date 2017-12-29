<?php

namespace Duo\BehaviorBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

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
	 * Get deletedBy
	 *
	 * @param UserInterface $deletedBy
	 *
	 * @return SoftDeleteInterface
	 */
	public function setDeletedBy(UserInterface $deletedBy = null): SoftDeleteInterface;

	/**
	 * Set deletedBy
	 *
	 * @return UserInterface
	 */
	public function getDeletedBy(): ?UserInterface;

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