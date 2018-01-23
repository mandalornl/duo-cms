<?php

namespace Duo\PartBundle\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Duo\PartBundle\Entity\EntityPartInterface;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Entity\PartReferenceInterface;

abstract class AbstractPartReferenceRepository extends EntityRepository implements PartReferenceRepositoryInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function addPartReference(EntityPartInterface $entity, PartInterface $part): PartReferenceInterface
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
	 * {@inheritdoc}
	 */
	public function addPartReferences(EntityPartInterface $entity): void
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
	 * @param EntityPartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return PartReferenceInterface
	 */
	protected function createPartReference(EntityPartInterface $entity, PartInterface $part): PartReferenceInterface
	{
		$class = $this->getClassName();

		/**
		 * @var PartReferenceInterface $partReference
		 */
		$partReference = new $class();

		return $partReference
			->setEntityId($entity->getId())
			->setPartId($part->getId())
			->setPartClass(ClassUtils::getClass($part))
			->setWeight($part->getWeight());
	}

	/**
	 * {@inheritdoc}
	 */
	public function removePartReference(EntityPartInterface $entity, PartInterface $part): bool
	{
		if (($weight = $this->getWeight($entity, $part)) !== null)
		{
			$this->updateWeightOfSiblingsOnRemove($entity, $weight);
		}

		$result = $this->getEntityManager()->createQueryBuilder()
			->delete($this->getClassName(), 'e')
			->where('e.entityId = :entityId')
			->andWhere('e.partId = :partId AND e.partClass = :partClass')
			->setParameter('entityId', $entity->getId())
			->setParameter('partId', $part->getId())
			->setParameter('partClass', ClassUtils::getClass($part))
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removePartReferences(EntityPartInterface $entity): bool
	{
		$result = $this->getEntityManager()
			->createQueryBuilder()
			->delete($this->getClassName(), 'e')
			->where('e.entityId = :entityId')
			->setParameter('entityId', $entity->getId())
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartReferences(EntityPartInterface $entity): array
	{
		return $this->findBy([
			'entityId' => $entity->getId()
		], [
			'weight' => 'ASC'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParts(EntityPartInterface $entity): array
	{
		/**
		 * @var PartReferenceInterface[] $references
		 */
		$references = $this->getPartReferences($entity);

		$sorting = [];

		$types = [];
		foreach ($references as $reference)
		{
			$types[$reference->getPartClass()][] = $reference->getPartId();

			// store sorting order
			$sorting[$reference->getPartClass() . $reference->getPartId()] = $reference->getWeight();
		}

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
	 * @param EntityPartInterface $entity
	 * @param PartInterface $part
	 *
	 * @return int
	 */
	protected function getWeight(EntityPartInterface $entity, PartInterface $part): ?int
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('e.weight')
				->where('e.entityId = :entityId')
				->andWhere('e.partId = :partId AND e.partClass = :partClass')
				->setParameter('entityId', $entity->getId())
				->setParameter('partId', $part->getId())
				->setParameter('partClass', ClassUtils::getClass($part))
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
	 * @param EntityPartInterface $entity
	 *
	 * @return int
	 */
	protected function getMaxWeight(EntityPartInterface $entity): int
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('MAX(e.weight)')
				->where('e.entityId = :entityId')
				->setParameter('entityId', $entity->getId())
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
	 * @param EntityPartInterface $entity
	 * @param int $weight
	 *
	 * @return bool
	 */
	protected function updateWeightOfSiblingsOnAdd(EntityPartInterface $entity, int $weight): bool
	{
		$className = $this->getClassName();

		$dql = <<<SQL
UPDATE {$className} e SET e.weight = e.weight + 1 
WHERE e.weight >= :weight
AND e.entityId = :entityId
SQL;

		$result = $this->getEntityManager()
			->createQuery($dql)
			->setParameter('weight', $weight)
			->setParameter('entityId', $entity->getId())
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Update weight of siblings on remove
	 *
	 * @param EntityPartInterface $entity
	 * @param int $weight
	 *
	 * @return bool
	 */
	protected function updateWeightOfSiblingsOnRemove(EntityPartInterface $entity, int $weight): bool
	{
		$className = $this->getClassName();

		$dql = <<<SQL
UPDATE {$className} e SET e.weight = e.weight - 1
WHERE e.weight > :weight
AND e.entityId = :entityId
SQL;

		$result = $this->getEntityManager()
			->createQuery($dql)
			->setParameter('weight', $weight)
			->setParameter('entityId', $entity->getId())
			->execute();

		return count($result) !== 0;
	}
}