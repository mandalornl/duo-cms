<?php

namespace Duo\PartBundle\Entity;

use Duo\BehaviorBundle\Entity\IdInterface;
use Duo\BehaviorBundle\Entity\TimestampInterface;

interface PartReferenceInterface extends IdInterface, TimestampInterface
{
	/**
	 * Set entityId
	 *
	 * @param int $entityId
	 *
	 * @return PartReferenceInterface
	 */
	public function setEntityId(int $entityId = null): PartReferenceInterface;

	/**
	 * Get entityId
	 *
	 * @return int
	 */
	public function getEntityId(): ?int;

	/**
	 * Set partId
	 *
	 * @param int $partId
	 *
	 * @return PartReferenceInterface
	 */
	public function setPartId(int $partId = null): PartReferenceInterface;

	/**
	 * Get partId
	 *
	 * @return int
	 */
	public function getPartId(): ?int;

	/**
	 * Set partClass
	 *
	 * @param string $partClass
	 *
	 * @return PartReferenceInterface
	 */
	public function setPartClass(string $partClass = null): PartReferenceInterface;

	/**
	 * Get partClass
	 *
	 * @return string
	 */
	public function getPartClass(): ?string;

	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return PartReferenceInterface
	 */
	public function setWeight(int $weight = 0): PartReferenceInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): int;
}