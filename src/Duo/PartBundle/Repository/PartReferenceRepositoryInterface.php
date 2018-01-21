<?php

namespace Duo\PartBundle\Repository;

use Duo\PartBundle\Entity\NodePartInterface;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Entity\PartReferenceInterface;

interface PartReferenceRepositoryInterface
{
	/**
	 * Add part reference
	 *
	 * @param NodePartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return PartReferenceInterface
	 */
	public function addPartReference(NodePartInterface $entity, PartInterface $part): PartReferenceInterface;

	/**
	 * Add part references
	 *
	 * @param NodePartInterface $entity
	 */
	public function addPartReferences(NodePartInterface $entity): void;

	/**
	 * Remove part reference
	 *
	 * @param NodePartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return bool
	 */
	public function removePartReference(NodePartInterface $entity, PartInterface $part): bool;

	/**
	 * Remove part references
	 *
	 * @param NodePartInterface $entity
	 *
	 * @return bool
	 */
	public function removePartReferences(NodePartInterface $entity): bool;

	/**
	 * Get part references
	 *
	 * @param NodePartInterface $entity
	 *
	 * @return PartReferenceInterface[]
	 */
	public function getPartReferences(NodePartInterface $entity): array;

	/**
	 * Get parts
	 *
	 * @param NodePartInterface $entity
	 *
	 * @return PartInterface[]
	 */
	public function getParts(NodePartInterface $entity): array;
}