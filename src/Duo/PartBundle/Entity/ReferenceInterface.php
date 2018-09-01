<?php

namespace Duo\PartBundle\Entity;

use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;

interface ReferenceInterface extends IdInterface, TimestampInterface
{
	/**
	 * Set entityId
	 *
	 * @param int $entityId
	 *
	 * @return ReferenceInterface
	 */
	public function setEntityId(int $entityId = null): ReferenceInterface;

	/**
	 * Get entityId
	 *
	 * @return int
	 */
	public function getEntityId(): ?int;

	/**
	 * Set entityClass
	 *
	 * @param string $entityClass
	 *
	 * @return ReferenceInterface
	 */
	public function setEntityClass(string $entityClass = null): ReferenceInterface;

	/**
	 * Get entityClass
	 *
	 * @return string
	 */
	public function getEntityClass(): ?string;

	/**
	 * Set partId
	 *
	 * @param int $partId
	 *
	 * @return ReferenceInterface
	 */
	public function setPartId(int $partId = null): ReferenceInterface;

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
	 * @return ReferenceInterface
	 */
	public function setPartClass(string $partClass = null): ReferenceInterface;

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
	 * @return ReferenceInterface
	 */
	public function setWeight(int $weight = null): ReferenceInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): ?int;

	/**
	 * Set section
	 *
	 * @param string $section
	 *
	 * @return ReferenceInterface
	 */
	public function setSection(string $section = null): ReferenceInterface;

	/**
	 * Get section
	 *
	 * @return string
	 */
	public function getSection(): ?string;
}