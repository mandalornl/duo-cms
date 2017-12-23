<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface BlameableInterface
{
	/**
	 * Set createdBy
	 *
	 * @param UserInterface $createdBy
	 *
	 * @return BlameableInterface
	 */
	public function setCreatedBy(UserInterface $createdBy = null): BlameableInterface;

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
	 * @return BlameableInterface
	 */
	public function setModifiedBy(UserInterface $modifiedBy = null): BlameableInterface;

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
	 * @return BlameableInterface
	 */
	public function setDeletedBy(UserInterface $deletedBy = null): BlameableInterface;

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