<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface BlameInterface
{
	/**
	 * Set createdBy
	 *
	 * @param UserInterface $createdBy
	 *
	 * @return BlameInterface
	 */
	public function setCreatedBy(UserInterface $createdBy = null): BlameInterface;

	/**
	 * Get createdBy
	 *
	 * @return UserInterface
	 */
	public function getCreatedBy(): ?UserInterface;

	/**
	 * Set modified by
	 *
	 * @param UserInterface $modifiedBy
	 *
	 * @return BlameInterface
	 */
	public function setModifiedBy(UserInterface $modifiedBy = null): BlameInterface;

	/**
	 * Get modifiedBy
	 *
	 * @return UserInterface
	 */
	public function getModifiedBy(): ?UserInterface;

	/**
	 * Set deletedBy
	 *
	 * @param UserInterface $deletedBy
	 *
	 * @return BlameInterface
	 */
	public function setDeletedBy(UserInterface $deletedBy = null): BlameInterface;

	/**
	 * Get deletedBy
	 *
	 * @return UserInterface
	 */
	public function getDeletedBy(): ?UserInterface;

	/**
	 * Get blameable entity class name
	 *
	 * @return string
	 */
	public static function getBlameableUserEntityClassName(): string;
}