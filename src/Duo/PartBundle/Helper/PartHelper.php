<?php

namespace Duo\PartBundle\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;
use Duo\PartBundle\Entity\Reference;
use Duo\PartBundle\Entity\ReferenceInterface;
use Duo\PartBundle\Repository\ReferenceRepositoryInterface;

class PartHelper
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * PartHelper constructor
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * Create part reference
	 *
	 * @param PropertyPartInterface $entity
	 * @param EntityPartInterface $part
	 *
	 * @return ReferenceInterface
	 */
	public function createPartReference(PropertyPartInterface $entity, EntityPartInterface $part): ReferenceInterface
	{
		return (new Reference())
			->setEntityId($entity->getId())
			->setEntityClass(get_class($entity))
			->setPartId($part->getId())
			->setPartClass(get_class($part))
			->setWeight($part->getWeight())
			->setSection($part->getSection());
	}

	/**
	 * Add reference
	 *
	 * @param PropertyPartInterface $entity
	 * @param EntityPartInterface $part
	 * @param bool $doPersist [optional]
	 *
	 * @return ReferenceInterface
	 */
	public function addReference(PropertyPartInterface $entity, EntityPartInterface $part, bool $doPersist = true): ReferenceInterface
	{
		// default behavior is to append
		if ($part->getWeight() === null)
		{
			$part->setWeight($this->getRepository()->findMaxWeight($entity));
		}
		else
		{
			// update weight of siblings if part is inserted somewhere between
			$this->updateWeightOfSiblingsOnAdd($entity, $part->getWeight(), $part->getSection());
		}

		$reference = $this->createPartReference($entity, $part);

		if (!$doPersist)
		{
			return $reference;
		}

		$this->manager->persist($reference);
		$this->manager->flush();

		return $reference;
	}

	/**
	 * Remove reference
	 *
	 * @param PropertyPartInterface $entity
	 * @param EntityPartInterface $part
	 *
	 * @return bool
	 */
	public function removeReference(PropertyPartInterface $entity, EntityPartInterface $part): bool
	{
		if (($weight = $this->getRepository()->findWeight($entity, $part)) !== null)
		{
			$this->updateWeightOfSiblingsOnRemove($entity, $weight, $part->getSection());
		}

		return $this->getRepository()->createQueryBuilder('e')
			->delete()
			->where('e.entityId = :entityId AND e.entityClass = :entityClass')
			->andWhere('e.partId = :partId AND e.partClass = :partClass')
			->setParameter('entityId', $entity->getId())
			->setParameter('entityClass', get_class($entity))
			->setParameter('partId', $part->getId())
			->setParameter('partClass', get_class($part))
			->getQuery()
			->execute() !== 0;
	}

	/**
	 * Add references
	 *
	 * @param PropertyPartInterface $entity
	 * @param EntityPartInterface[] $parts [optional]
	 * @param bool $doPersist [optional]
	 *
	 * @return ReferenceInterface[]
	 */
	public function addReferences(PropertyPartInterface $entity, array $parts = [], bool $doPersist = true): array
	{
		$weight = null;

		$references = [];

		foreach ($parts ?: $entity->getParts() as $part)
		{
			if ($part->getWeight() === null)
			{
				// cached weight is unset
				if ($weight === null)
				{
					// look for available max weight in collection
					$weight = max(array_map(function(EntityPartInterface $part)
					{
						return $part->getWeight();
					}, $entity->getParts()->toArray()));

					// weight is still unknown
					if ($weight === null)
					{
						// consult database or default to zero
						$weight = $this->getRepository()->findMaxWeight($entity);

						// increase when max > 0
						if ($weight > 0)
						{
							$weight++;
						}
					}
					else
					{
						// increase when found in collection
						$weight++;
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
			 * @var EntityPartInterface $part
			 */
			$reference = $this->createPartReference($entity, $part);

			$references[] = $reference;

			if ($doPersist)
			{
				$this->manager->persist($reference);
			}
		}

		if ($doPersist)
		{
			$this->manager->flush();
		}

		return $references;
	}

	/**
	 * Update weight of siblings on add
	 *
	 * @param PropertyPartInterface $entity
	 * @param int $weight
	 * @param string $section [optional]
	 *
	 * @return bool
	 */
	public function updateWeightOfSiblingsOnAdd(PropertyPartInterface $entity, int $weight, string $section = null): bool
	{
		$builder = $this->getRepository()->createQueryBuilder('e')
			->update()
			->set('e.weight', 'e.weight + 1')
			->where('e.entityId = :entityId AND e.entityClass = :entityClass')
			->andWhere('e.weight >= :weight')
			->setParameter('entityId', $entity->getId())
			->setParameter('entityClass', get_class($entity))
			->setParameter('weight', $weight);

		if ($section !== null)
		{
			$builder
				->andWhere('e.section = :section')
				->setParameter('section', $section);
		}

		$result = $builder
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Update weight of siblings on remove
	 *
	 * @param PropertyPartInterface $entity
	 * @param int $weight
	 * @param string $section [optional]
	 *
	 * @return bool
	 */
	public function updateWeightOfSiblingsOnRemove(PropertyPartInterface $entity, int $weight, string $section = null): bool
	{
		$builder = $this->getRepository()->createQueryBuilder('e')
			->update()
			->set('e.weight', 'e.weight - 1')
			->where('e.entityId = :entityId AND e.entityClass = :entityClass')
			->andWhere('e.weight > :weight')
			->setParameter('entityId', $entity->getId())
			->setParameter('entityClass', get_class($entity))
			->setParameter('weight', $weight);

		if ($section !== null)
		{
			$builder
				->andWhere('e.section = :section')
				->setParameter('section', $section);
		}

		$result = $builder
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Get repository
	 *
	 * @return EntityRepository|ReferenceRepositoryInterface
	 */
	private function getRepository(): ReferenceRepositoryInterface
	{
		return $this->manager->getRepository(Reference::class);
	}
}