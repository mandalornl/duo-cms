<?php

namespace Duo\PartBundle\Entity;

use Duo\CoreBundle\Entity\Property\IdInterface;

interface ReferenceInterface extends IdInterface
{
	/**
	 * Set entityId
	 *
	 * @param int $entityId
	 *
	 * @return ReferenceInterface
	 */
	public function setEntityId(?int $entityId): ReferenceInterface;

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
	public function setEntityClass(?string $entityClass): ReferenceInterface;

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
	public function setPartId(?int $partId): ReferenceInterface;

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
	public function setPartClass(?string $partClass): ReferenceInterface;

	/**
	 * Get partClass
	 *
	 * @return string
	 */
	public function getPartClass(): ?string;
}