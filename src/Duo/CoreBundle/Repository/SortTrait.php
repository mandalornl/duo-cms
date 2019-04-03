<?php

namespace Duo\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;

trait SortTrait
{
	/**
	 * {@inheritDoc}
	 */
	public function findPrevToSort(SortInterface $entity): ?SortInterface
	{
		try
		{
			return $this->getPrevToSortQueryBuilder($entity)
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
	 * {@inheritDoc}
	 */
	public function findPrevAllToSort(SortInterface $entity): array
	{
		return $this->getPrevToSortQueryBuilder($entity)
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
	private function getPrevToSortQueryBuilder(SortInterface $entity): QueryBuilder
	{
		return $this->getSortQueryBuilder($entity)
			->andWhere('e.weight < :weight')
			->setParameter('weight', $entity->getWeight());
	}

	/**
	 * {@inheritDoc}
	 */
	public function findNextAllToSort(SortInterface $entity): array
	{
		return $this->getNextToSortQueryBuilder($entity)
			->orderBy('e.weight', 'ASC')
			->getQuery()
			->getResult();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findNextToSort(SortInterface $entity): ?SortInterface
	{
		try
		{
			return $this->getNextToSortQueryBuilder($entity)
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
	private function getNextToSortQueryBuilder(SortInterface $entity): QueryBuilder
	{
		return $this->getSortQueryBuilder($entity)
			->andWhere('e.weight > :weight')
			->setParameter('weight', $entity->getWeight());
	}

	/**
	 * {@inheritDoc}
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

		// ignore deleted
		if ($entity instanceof DeleteInterface)
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		return $builder;
	}
}
