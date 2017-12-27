<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Symfony\Component\Security\Core\User\UserInterface;

interface TimeStampInterface
{
	/**
	 * Set created at
	 *
	 * @param \DateTime $createdAt
	 *
	 * @return TimeStampInterface
	 */
	public function setCreatedAt(\DateTime $createdAt = null): TimeStampInterface;

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
	 * @return TimeStampInterface
	 */
	public function setModifiedAt(\DateTime $modifiedAt = null): TimeStampInterface;

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
	 * @return TimeStampInterface
	 */
	public function setCreatedBy(UserInterface $createdBy = null): TimeStampInterface;

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
	 * @return TimeStampInterface
	 */
	public function setModifiedBy(UserInterface $modifiedBy = null): TimeStampInterface;

	/**
	 * Get modifiedBy
	 *
	 * @return UserInterface
	 */
	public function getModifiedBy(): ?UserInterface;
}