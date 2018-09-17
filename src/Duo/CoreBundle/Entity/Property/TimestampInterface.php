<?php

namespace Duo\CoreBundle\Entity\Property;

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
	public function setCreatedAt(?\DateTime $createdAt): TimestampInterface;

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
	public function setModifiedAt(?\DateTime $modifiedAt): TimestampInterface;

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