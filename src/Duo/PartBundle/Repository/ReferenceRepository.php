<?php

namespace Duo\PartBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;
use Duo\PartBundle\Entity\ReferenceInterface;

class ReferenceRepository extends EntityRepository implements ReferenceRepositoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function findReferences(PropertyPartInterface $entity): array
	{
		return $this->findBy([
			'entityId' => $entity->getId(),
			'entityClass' => get_class($entity)
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function findReference(PropertyPartInterface $entity, EntityPartInterface $part): ?ReferenceInterface
	{
		/**
		 * @var ReferenceInterface $reference
		 */
		$reference = $this->findOneBy([
			'entityId' => $entity->getId(),
			'entityClass' => get_class($entity),
			'partId' => $part->getId(),
			'partClass' => get_class($part)
		]);

		return $reference;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findParts(PropertyPartInterface $entity): array
	{
		/**
		 * @var ReferenceInterface[] $references
		 */
		$references = $this->findReferences($entity);

		if (!count($references))
		{
			return [];
		}

		$types = [];

		foreach ($references as $reference)
		{
			$types[$reference->getPartClass()][] = $reference->getPartId();
		}

		/**
		 * @var PartInterface[] $parts
		 */
		$parts = [];

		foreach ($types as $partClass => $ids)
		{
			$parts = array_merge(
				$parts,
				$this->getEntityManager()
					->getRepository($partClass)
					->findBy([
						'id' => $ids
					])
			);
		}

		// sort parts on section and weight
		usort($parts, function(EntityPartInterface $a, EntityPartInterface $b)
		{
			if ($a->getSection() === $b->getSection())
			{
				return $a->getWeight() - $b->getWeight();
			}

			return strcmp($a->getSection(), $b->getSection());
		});

		return $parts;
	}
}
