<?php

namespace Duo\PartBundle\Repository;

use Duo\PartBundle\Entity\EntityPartInterface;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Entity\PartReferenceInterface;

interface PartReferenceRepositoryInterface
{
	/**
	 * Add part reference
	 *
	 * @param EntityPartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return PartReferenceInterface
	 */
	public function addPartReference(EntityPartInterface $entity, PartInterface $part): PartReferenceInterface;

	/**
	 * Add part references
	 *
	 * @param EntityPartInterface $entity
	 */
	public function addPartReferences(EntityPartInterface $entity): void;

	/**
	 * Remove part reference
	 *
	 * @param EntityPartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return bool
	 */
	public function removePartReference(EntityPartInterface $entity, PartInterface $part): bool;

	/**
	 * Remove part references
	 *
	 * @param EntityPartInterface $entity
	 *
	 * @return bool
	 */
	public function removePartReferences(EntityPartInterface $entity): bool;

	/**
	 * Remove part references by entity id
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function removePartReferencesByEntityId(int $id): bool;

	/**
	 * Get part references
	 *
	 * @param EntityPartInterface $entity
	 *
	 * @return PartReferenceInterface[]
	 */
	public function getPartReferences(EntityPartInterface $entity): array;

	/**
	 * Get parts
	 *
	 * @param EntityPartInterface $entity
	 *
	 * @return PartInterface[]
	 */
	public function getParts(EntityPartInterface $entity): array;
}