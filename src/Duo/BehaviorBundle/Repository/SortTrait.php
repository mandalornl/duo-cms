<?php

namespace Duo\BehaviorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\RevisionInterface;

trait SortTrait
{
	/**
	 * Find previous to sort
	 *
	 * @param SortInterface $entity
	 *
	 * @return SortInterface
	 */
	public function findPrevToSort(SortInterface $entity): ?SortInterface
	{
		try
		{
			return $this->getPrevQueryBuilder($entity)
				->orderBy('e.weight', 'DESC')
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * Find previous all to sort
	 *
	 * @param SortInterface $entity
	 *
	 * @return SortInterface[]
	 */
	public function findPrevAllToSort(SortInterface $entity): array
	{
		return $this->getPrevQueryBuilder($entity)
			->orderBy('e.weight', 'ASC')
			->getQuery()
			->getResult();
	}

	/**
	 * Get previous query builder
	 *
	 * @param SortInterface $entity
	 *
	 * @return QueryBuilder
	 */
	private function getPrevQueryBuilder(SortInterface $entity): QueryBuilder
	{
		return $this->getSortQueryBuilder($entity)
			->andWhere('e.weight < :weight')
			->setParameter('weight', $entity->getWeight());
	}

	/**
	 * Find next all to sort
	 *
	 * @param SortInterface $entity
	 *
	 * @return SortInterface[]
	 */
	public function findNextAllToSort(SortInterface $entity): array
	{
		return $this->getNextQueryBuilder($entity)
			->orderBy('e.weight', 'ASC')
			->getQuery()
			->getResult();
	}

	/**
	 * Find next entity to sort
	 *
	 * @param SortInterface $entity
	 *
	 * @return SortInterface
	 */
	public function findNextToSort(SortInterface $entity): ?SortInterface
	{
		try
		{
			return $this->getNextQueryBuilder($entity)
				->orderBy('e.weight', 'ASC')
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}

	}

	/**
	 * Get next query builder
	 *
	 * @param SortInterface $entity
	 *
	 * @return QueryBuilder
	 */
	private function getNextQueryBuilder(SortInterface $entity): QueryBuilder
	{
		return $this->getSortQueryBuilder($entity)
			->andWhere('e.weight > :weight')
			->setParameter('weight', $entity->getWeight());
	}

	/**
	 * Find siblings to sort
	 *
	 * @param SortInterface $entity
	 *
	 * @return SortInterface[]
	 */
	public function findSiblingsToSort(SortInterface $entity): array
	{
		return $this->getSortQueryBuilder($entity)
			->orderBy('e.weight', 'ASC')
			->getQuery()
			->getResult();
	}

	/**
	 * Get sort query builder
	 *
	 * @param SortInterface $entity
	 *
	 * @return QueryBuilder
	 */
	private function getSortQueryBuilder(SortInterface $entity): QueryBuilder
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e');

		// use parent of entity
		if ($entity instanceof TreeInterface)
		{
			if (($parent = $entity->getParent()) !== null)
			{
				$builder
					->andWhere('e.parent = :parent')
					->setParameter('parent', $parent);
			}
			else
			{
				$builder->andWhere('e.parent IS NULL');
			}
		}

		// use latest revision
		if ($entity instanceof RevisionInterface)
		{
			$builder->andWhere('e.revision = e.id');
		}

		// ignore deleted
		if ($entity instanceof DeleteInterface)
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		return $builder;
	}
}