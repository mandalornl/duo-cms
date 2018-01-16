<?php

namespace Duo\PagePartBundle\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Duo\PagePartBundle\Entity\NodePagePartInterface;
use Duo\PagePartBundle\Entity\PagePartInterface;
use Duo\PagePartBundle\Entity\PagePartReference;

class PagePartReferenceRepository extends EntityRepository
{
	/**
	 * Add page part reference
	 *
	 * @param NodePagePartInterface $entity
	 * @param PagePartInterface $pagePart
	 *
	 * @return PagePartReference
	 */
	public function addPagePartReference(NodePagePartInterface $entity, PagePartInterface $pagePart): PagePartReference
	{
		// default behavior is to append page part
		if ($pagePart->getWeight() === null)
		{
			$pagePart->setWeight($this->getMaxWeight($entity));
		}
		else
		{
			// update weight of siblings if page part is inserted somewhere between
			$this->updateWeightOfSiblingsOnAdd($entity, $pagePart->getWeight());
		}

		$pagePartReference = $this->createPagePartReference($entity, $pagePart);

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getEntityManager();
		$em->persist($pagePartReference);
		$em->flush();

		return $pagePartReference;
	}

	/**
	 * Add page part references
	 *
	 * @param NodePagePartInterface $entity
	 */
	public function addPagePartReferences(NodePagePartInterface $entity)
	{
		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getEntityManager();

		foreach ($entity->getPageParts() as $weight => $pagePart)
		{
			/**
			 * @var PagePartInterface $pagePart
			 */
			if ($pagePart->getWeight() === null)
			{
				$pagePart->setWeight($weight);
			}

			/**
			 * @var PagePartInterface $pagePart
			 */
			$pagePartReference = $this->createPagePartReference($entity, $pagePart);

			$em->persist($pagePartReference);
		}

		$em->flush();
	}

	/**
	 * Get page part reference
	 *
	 * @param NodePagePartInterface $entity
	 * @param PagePartInterface $pagePart
	 *
	 * @return PagePartReference
	 */
	private function createPagePartReference(NodePagePartInterface $entity, PagePartInterface $pagePart): PagePartReference
	{
		return (new PagePartReference())
			->setEntityId($entity->getId())
			->setEntityFqcn(ClassUtils::getClass($entity))
			->setPagePartId($pagePart->getId())
			->setPagePartFqcn(ClassUtils::getClass($pagePart))
			->setWeight($pagePart->getWeight());
	}

	/**
	 * Remove page part reference
	 *
	 * @param NodePagePartInterface $entity
	 * @param PagePartInterface $pagePart
	 *
	 * @return bool
	 */
	public function removePagePartReference(NodePagePartInterface $entity, PagePartInterface $pagePart): bool
	{
		if (($weight = $this->getWeight($entity, $pagePart)) !== null)
		{
			$this->updateWeightOfSiblingsOnRemove($entity, $weight);
		}

		$result = $this->getEntityManager()->createQueryBuilder()
			->delete(PagePartReference::class, 'e')
			->where('e.entityId = :entityId AND e.entityFqcn = :entityFqcn')
			->andWhere('e.pagePartId = :pagePartId AND e.pagePartFqcn = :pagePartFqcn')
			->setParameter('entityId', $entity->getId())
			->setParameter('entityFqcn', ClassUtils::getClass($entity))
			->setParameter('pagePartId', $pagePart->getId())
			->setParameter('pagePartFqcn', ClassUtils::getClass($pagePart))
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Remove page part references
	 *
	 * @param NodePagePartInterface $entity
	 *
	 * @return bool
	 */
	public function removePagePartReferences(NodePagePartInterface $entity): bool
	{
		$result = $this->getEntityManager()
			->createQueryBuilder()
			->delete(PagePartReference::class, 'e')
			->where('e.entityId = :entityId AND e.entityFqcn = :entityFqcn')
			->setParameter('entityId', $entity->getId())
			->setParameter('entityFqcn', ClassUtils::getClass($entity))
			->getQuery()
			->execute();

		return count($result) !== 0;
	}

	/**
	 * Get page part references
	 *
	 * @param NodePagePartInterface $entity
	 *
	 * @return PagePartReference[]
	 */
	public function getPagePartReferences(NodePagePartInterface $entity): array
	{
		return $this->findBy([
			'entityId' => $entity->getId(),
			'entityFqcn' => ClassUtils::getClass($entity)
		], [
			'weight' => 'ASC'
		]);
	}

	/**
	 * Get page parts
	 *
	 * @param NodePagePartInterface $entity
	 *
	 * @return PagePartInterface[]
	 */
	public function getPageParts(NodePagePartInterface $entity): array
	{
		/**
		 * @var PagePartReference[] $references
		 */
		$references = $this->getPagePartReferences($entity);

		$sorting = [];

		$types = [];
		foreach ($references as $reference)
		{
			$types[$reference->getPagePartFqcn()][] = $reference->getPagePartId();

			// store sorting order
			$sorting[$reference->getPagePartFqcn() . $reference->getPagePartId()] = $reference->getWeight();
		}

		$pageParts = [];
		foreach ($types as $pagePartFqcn => $ids)
		{
			$pageParts = array_merge(
				$pageParts,
				$this->getEntityManager()
					->getRepository($pagePartFqcn)
					->findBy([
						'id' => $ids
					])
			);
		}

		// sort page parts using sorting order
		usort($pageParts, function(PagePartInterface $a, PagePartInterface $b) use ($sorting)
		{
			$aWeight = $sorting[ClassUtils::getClass($a) . $a->getId()];
			$bWeight = $sorting[ClassUtils::getClass($b) . $b->getId()];

			return $aWeight - $bWeight;
		});

		return $pageParts;
	}

	/**
	 * Get weight
	 *
	 * @param NodePagePartInterface $entity
	 * @param PagePartInterface $pagePart
	 *
	 * @return int
	 */
	private function getWeight(NodePagePartInterface $entity, PagePartInterface $pagePart): ?int
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('e.weight')
				->where('e.entityId = :entityId AND e.entityFqcn = :entityFqcn')
				->andWhere('e.pagePartId = :pagePartId AND e.pagePartFqcn = :pagePartFqcn')
				->setParameter('entityId', $entity->getId())
				->setParameter('entityFqcn', ClassUtils::getClass($entity))
				->setParameter('pagePartId', $pagePart->getId())
				->setParameter('pagePartFqcn', ClassUtils::getClass($pagePart))
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
	 * @param NodePagePartInterface $entity
	 *
	 * @return int
	 */
	private function getMaxWeight(NodePagePartInterface $entity): int
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
	 * @param NodePagePartInterface $entity
	 * @param int $weight
	 *
	 * @return bool
	 */
	private function updateWeightOfSiblingsOnAdd(NodePagePartInterface $entity, int $weight): bool
	{
		$className = PagePartReference::class;

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
	 * @param NodePagePartInterface $entity
	 * @param int $weight
	 *
	 * @return bool
	 */
	private function updateWeightOfSiblingsOnRemove(NodePagePartInterface $entity, int $weight): bool
	{
		$className = PagePartReference::class;

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