<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

interface TimeStampableInterface
{
	/**
	 * Set created at
	 *
	 * @param \DateTime $createdAt
	 *
	 * @return TimeStampableInterface
	 */
	public function setCreatedAt(\DateTime $createdAt = null): TimeStampableInterface;

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
	 * @return TimeStampableInterface
	 */
	public function setModifiedAt(\DateTime $modifiedAt = null): TimeStampableInterface;

	/**
	 * Get modified at
	 *
	 * @return \DateTime
	 */
	public function getModifiedAt(): ?\DateTime;
}