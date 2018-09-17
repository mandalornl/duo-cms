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
}