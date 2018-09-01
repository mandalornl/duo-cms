<?php

namespace Duo\PartBundle\Repository;

use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;
use Duo\PartBundle\Entity\ReferenceInterface;

interface ReferenceRepositoryInterface
{
	/**
	 * Find references
	 *
	 * @param PropertyPartInterface $entity
	 *
	 * @return ReferenceInterface[]
	 */
	public function findReferences(PropertyPartInterface $entity): array;

	/**
	 * Find reference
	 *
	 * @param PropertyPartInterface $entity
	 * @param EntityPartInterface $part
	 *
	 * @return ReferenceInterface
	 */
	public function findReference(PropertyPartInterface $entity, EntityPartInterface $part): ?ReferenceInterface;

	/**
	 * Find parts
	 *
	 * @param PropertyPartInterface $entity
	 *
	 * @return EntityPartInterface[]
	 */
	public function findParts(PropertyPartInterface $entity): array;

	/**
	 * Find weight
	 *
	 * @param PropertyPartInterface $entity
	 * @param EntityPartInterface $part
	 *
	 * @return int
	 */
	public function findWeight(PropertyPartInterface $entity, EntityPartInterface $part): ?int;

	/**
	 * Find max weight
	 *
	 * @param PropertyPartInterface $entity
	 *
	 * @return int
	 */
	public function findMaxWeight(PropertyPartInterface $entity): int;
}