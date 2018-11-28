<?php

namespace Duo\CoreBundle\Entity\Property;

use Symfony\Component\Security\Core\User\UserInterface;

interface TimestampInterface
{
	/**
	 * Set created at
	 *
	 * @param \DateTimeInterface $createdAt
	 *
	 * @return TimestampInterface
	 */
	public function setCreatedAt(?\DateTimeInterface $createdAt): TimestampInterface;

	/**
	 * Get created at
	 *
	 * @return \DateTimeInterface
	 */
	public function getCreatedAt(): ?\DateTimeInterface;

	/**
	 * Set modified at
	 *
	 * @param \DateTimeInterface $modifiedAt
	 *
	 * @return TimestampInterface
	 */
	public function setModifiedAt(?\DateTimeInterface $modifiedAt): TimestampInterface;

	/**
	 * Get modified at
	 *
	 * @return \DateTimeInterface
	 */
	public function getModifiedAt(): ?\DateTimeInterface;

	/**
	 * Set createdBy
	 *
	 * @param UserInterface $createdBy
	 *
	 * @return TimestampInterface
	 */
	public function setCreatedBy(?UserInterface $createdBy): TimestampInterface;

	/**
	 * Get createdBy
	 *
	 * @return UserInterface
	 */
	public function getCreatedBy(): ?UserInterface;

	/**
	 * Set modifiedBy
	 *
	 * @param UserInterface $modifiedBy
	 *
	 * @return TimestampInterface
	 */
	public function setModifiedBy(?UserInterface $modifiedBy): TimestampInterface;

	/**
	 * Get modifiedBy
	 *
	 * @return UserInterface
	 */
	public function getModifiedBy(): ?UserInterface;
}
