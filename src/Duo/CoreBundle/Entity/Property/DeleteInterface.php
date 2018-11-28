<?php

namespace Duo\CoreBundle\Entity\Property;

use Symfony\Component\Security\Core\User\UserInterface;

interface DeleteInterface
{
	/**
	 * Set deletedAt
	 *
	 * @param \DateTimeInterface $deletedAt
	 *
	 * @return DeleteInterface
	 */
	public function setDeletedAt(\DateTimeInterface $deletedAt): DeleteInterface;

	/**
	 * Get deletedAt
	 *
	 * @return \DateTimeInterface
	 */
	public function getDeletedAt(): ?\DateTimeInterface;

	/**
	 * Get deletedBy
	 *
	 * @param UserInterface $deletedBy
	 *
	 * @return DeleteInterface
	 */
	public function setDeletedBy(?UserInterface $deletedBy): DeleteInterface;

	/**
	 * Set deletedBy
	 *
	 * @return UserInterface
	 */
	public function getDeletedBy(): ?UserInterface;

	/**
	 * Delete entity
	 *
	 * @return DeleteInterface
	 */
	public function delete(): DeleteInterface;

	/**
	 * Undelete entity
	 *
	 * @return DeleteInterface
	 */
	public function undelete(): DeleteInterface;

	/**
	 * Check whether or not entity is deleted
	 *
	 * @return bool
	 */
	public function isDeleted(): bool;
}
