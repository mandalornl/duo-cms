<?php

namespace Duo\AdminBundle\Entity\Behavior;

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
}