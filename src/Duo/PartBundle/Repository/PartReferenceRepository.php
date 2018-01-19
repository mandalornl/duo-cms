<?php

namespace Duo\PartBundle\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Duo\PartBundle\Entity\NodePartInterface;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Entity\PartReference;

class PartReferenceRepository extends EntityRepository
{
	/**
	 * Add part reference
	 *
	 * @param NodePartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return PartReference
	 */
	public function addPartReference(NodePartInterface $entity, PartInterface $part): PartReference
	{
		// default behavior is to append part
		if ($part->getWeight() === null)
		{
			$part->setWeight($this->getMaxWeight($entity));
		}
		else
		{
			// update weight of siblings if part is inserted somewhere between
			$this->updateWeightOfSiblingsOnAdd($entity, $part->getWeight());
		}

		$partReference = $this->createPartReference($entity, $part);

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getEntityManager();
		$em->persist($partReference);
		$em->flush();

		return $partReference;
	}

	/**
	 * Add part references
	 *
	 * @param NodePartInterface $entity
	 */
	public function addPartReferences(NodePartInterface $entity)
	{
		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getEntityManager();

		$weight = null;

		foreach ($entity->getParts() as $part)
		{
			/**
			 * @var PartInterface $part
			 */
			if ($part->getWeight() === null)
			{
				// cached weight is unset
				if ($weight === null)
				{
					// look for available max weight in collection
					$weight = max(array_map(function(PartInterface $part)
					{
						return $part->getWeight();
					}, $entity->getParts()->toArray()));

					// weight is still unknown
					if ($weight === null)
					{
						// consult database or default to zero
						$weight = $this->getMaxWeight($entity);
					}
				}
				else
				{
					// just increase cached weight instead
					$weight++;
				}

				$part->setWeight($weight);
			}

			/**
			 * @var PartInterface $part
			 */
			$partReference = $this->createPartReference($entity, $part);

			$em->persist($partReference);
		}

		$em->flush();
	}

	/**
	 * Get part reference
	 *
	 * @param NodePartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return PartReference
	 */
	private function createPartReference(NodePartInterface $entity, PartInterface $part): PartReference
	{
		return (new PartReference())
			->setEntityId($entity->getId())
			->setEntityFqcn(ClassUtils::getClass($entity))
			->setPartId($part->getId())
			->setPartFqcn(ClassUtils::getClass($part))
			->setWeight($part->getWeight());
	}

	/**
	 * Remove part reference
	 *
	 * @param NodePartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return bool
	 */
	public function removePartReference(NodePartInterface $entity, PartInterface $part): bool
	{
		if (($weight = $this->getWeight($entity, $part)) !== null)
		{
			$this->updateWeightOfSiblingsOnRemove($entity, $weight);
		}

		$result = $this->getEntityManager()->createQueryBuilder()
			->delete(PartReference::class, 'e')
			->where('e.entityId = :entityId AND e.entityFqcn = :entityFqcn')
			->andWhere('e.partId = :partId AND e.partFqcn = :partFqcn')
			->setParameter('entityId', $entity->getId())
			->setParameter('entityFqcn', ClassUtils::getClass($entity))
			->setParameter('partId', $part->getId())
			->setParameter('partFqcn', ClassUtils::getClass($part))
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Remove part references
	 *
	 * @param NodePartInterface $entity
	 *
	 * @return bool
	 */
	public function removePartReferences(NodePartInterface $entity): bool
	{
		$result = $this->getEntityManager()
			->createQueryBuilder()
			->delete(PartReference::class, 'e')
			->where('e.entityId = :entityId AND e.entityFqcn = :entityFqcn')
			->setParameter('entityId', $entity->getId())
			->setParameter('entityFqcn', ClassUtils::getClass($entity))
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Get part references
	 *
	 * @param NodePartInterface $entity
	 *
	 * @return PartReference[]
	 */
	public function getPartReferences(NodePartInterface $entity): array
	{
		return $this->findBy([
			'entityId' => $entity->getId(),
			'entityFqcn' => ClassUtils::getClass($entity)
		], [
			'weight' => 'ASC'
		]);
	}

	/**
	 * Get parts
	 *
	 * @param NodePartInterface $entity
	 *
	 * @return PartInterface[]
	 */
	public function getParts(NodePartInterface $entity): array
	{
		/**
		 * @var PartReference[] $references
		 */
		$references = $this->getPartReferences($entity);

		$sorting = [];

		$types = [];
		foreach ($references as $reference)
		{
			$types[$reference->getPartFqcn()][] = $reference->getPartId();

			// store sorting order
			$sorting[$reference->getPartFqcn() . $reference->getPartId()] = $reference->getWeight();
		}

		$parts = [];
		foreach ($types as $partFqcn => $ids)
		{
			$parts = array_merge(
				$parts,
				$this->getEntityManager()
					->getRepository($partFqcn)
					->findBy([
						'id' => $ids
					])
			);
		}

		// assign weight
		foreach ($parts as $part)
		{
			$part->setWeight($sorting[ClassUtils::getClass($part) . $part->getId()]);
		}

		// sort parts using weight
		usort($parts, function(PartInterface $a, PartInterface $b) use ($sorting)
		{
			return $a->getWeight() - $b->getWeight();
		});

		return $parts;
	}

	/**
	 * Get weight
	 *
	 * @param NodePartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return int
	 */
	private function getWeight(NodePartInterface $entity, PartInterface $part): ?int
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('e.weight')
				->where('e.entityId = :entityId AND e.entityFqcn = :entityFqcn')
				->andWhere('e.partId = :partId AND e.partFqcn = :partFqcn')
				->setParameter('entityId', $entity->getId())
				->setParameter('entityFqcn', ClassUtils::getClass($entity))
				->setParameter('partId', $part->getId())
				->setParameter('partFqcn', ClassUtils::getClass($part))
				->getQuery()
				->getSingleScalarResult();
		}
		catch (NoResultException | NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * Get max weight
	 *
	 * @param NodePartInterface $entity
	 *
	 * @return int
	 */
	private function getMaxWeight(NodePartInterface $entity): int
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('MAX(e.weight)')
				->where('e.entityId = :entityId AND e.entityFqcn = :entityFqcn')
				->setParameter('entityId', $entity->getId())
				->setParameter('entityFqcn', ClassUtils::getClass($entity))
				->getQuery()
				->getSingleScalarResult();
		}
		catch (NoResultException | NonUniqueResultException $e)
		{
			return 0;
		}
	}

	/**
	 * Update weight of siblings on add
	 *
	 * @param NodePartInterface $entity
	 * @param int $weight
	 *
	 * @return bool
	 */
	private function updateWeightOfSiblingsOnAdd(NodePartInterface $entity, int $weight): bool
	{
		$className = PartReference::class;

		$dql = <<<SQL
UPDATE {$className} e SET e.weight = e.weight + 1 
WHERE e.weight >= :weight
AND e.entityId = :entityId
AND e.entityFqcn = :entityFqcn
SQL;

		$result = $this->getEntityManager()
			->createQuery($dql)
			->setParameter('weight', $weight)
			->setParameter('entityId', $entity->getId())
			->setParameter('entityFqcn', ClassUtils::getClass($entity))
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Update weight of siblings on remove
	 *
	 * @param NodePartInterface $entity
	 * @param int $weight
	 *
	 * @return bool
	 */
	private function updateWeightOfSiblingsOnRemove(NodePartInterface $entity, int $weight): bool
	{
		$className = PartReference::class;

		$dql = <<<SQL
UPDATE {$className} e SET e.weight = e.weight - 1
WHERE e.weight > :weight
AND e.entityId = :entityId
AND e.entityFqcn = :entityFqcn
SQL;

		$result = $this->getEntityManager()
			->createQuery($dql)
			->setParameter('weight', $weight)
			->setParameter('entityId', $entity->getId())
			->setParameter('entityFqcn', ClassUtils::getClass($entity))
			->execute();

		return count($result) !== 0;
	}
}