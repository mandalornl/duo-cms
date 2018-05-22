<?php

namespace Duo\CoreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface TimestampInterface
{
	/**
	 * Set created at
	 *
	 * @param \DateTime $createdAt
	 *
	 * @return TimestampInterface
	 */
	public function setCreatedAt(\DateTime $createdAt = null): TimestampInterface;

	/**
	 * Get created at
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt(): ?\DateTime;

	/**
	 * Set modified at
	 *
	 * @param \DateTime $modifiedAt
	 *
	 * @return TimestampInterface
	 */
	public function setModifiedAt(\DateTime $modifiedAt = null): TimestampInterface;

	/**
	 * Get modified at
	 *
	 * @return \DateTime
	 */
	public function getModifiedAt(): ?\DateTime;

	/**
	 * Set createdBy
	 *
	 * @param UserInterface $createdBy
	 *
	 * @return TimestampInterface
	 */
	public function setCreatedBy(UserInterface $createdBy = null): TimestampInterface;

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
	public function setModifiedBy(UserInterface $modifiedBy = null): TimestampInterface;

	/**
	 * Get modifiedBy
	 *
	 * @return UserInterface
	 */
	public function getModifiedBy(): ?UserInterface;
}