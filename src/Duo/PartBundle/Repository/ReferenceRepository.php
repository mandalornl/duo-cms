<?php

namespace Duo\PartBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;
use Duo\PartBundle\Entity\ReferenceInterface;

class ReferenceRepository extends EntityRepository implements ReferenceRepositoryInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function findReferences(PropertyPartInterface $entity): array
	{
		return $this->findBy([
			'entityId' => $entity->getId(),
			'entityClass' => get_class($entity)
		], [
			'section' => 'ASC',
			'weight' => 'ASC'
		]);
	}

	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
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
		$properties = [];

		foreach ($references as $reference)
		{
			$types[$reference->getPartClass()][] = $reference->getPartId();

			// store part properties
			$properties[$reference->getPartClass() . $reference->getPartId()] = [
				'weight' => $reference->getWeight(),
				'section' => $reference->getSection()
			];
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

		// assign part properties
		foreach ($parts as $part)
		{
			$oid = get_class($part) . $part->getId();

			$part
				->setWeight($properties[$oid]['weight'])
				->setSection($properties[$oid]['section']);
		}

		// sort parts using weight
		usort($parts, function(EntityPartInterface $a, EntityPartInterface $b)
		{
			return $a->getWeight() - $b->getWeight();
		});

		return $parts;
	}

	/**
	 * {@inheritdoc}
	 */
	public function findWeight(PropertyPartInterface $entity, EntityPartInterface $part): ?int
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('e.weight')
				->where('e.entityId = :entityId AND e.entityClass = :entityClass')
				->andWhere('e.partId = :partId AND e.partClass = :partClass')
				->setParameter('entityId', $entity->getId())
				->setParameter('entityClass', get_class($entity))
				->setParameter('partId', $part->getId())
				->setParameter('partClass', get_class($part))
				->getQuery()
				->getSingleScalarResult() ?: null;
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function findMaxWeight(PropertyPartInterface $entity): int
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('MAX(e.weight)')
				->where('e.entityId = :entityId AND e.entityClass = :entityClass')
				->setParameter('entityId', $entity->getId())
				->setParameter('entityClass', get_class($entity))
				->getQuery()
				->getSingleScalarResult();
		}
		catch (NonUniqueResultException $e)
		{
			return 0;
		}
	}
}