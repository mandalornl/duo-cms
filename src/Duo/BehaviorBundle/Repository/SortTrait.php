<?php

namespace Duo\BehaviorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Duo\BehaviorBundle\Entity\SoftDeleteInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;

trait SortTrait
{
	/**
	 * Find next weight
	 *
	 * @param SortInterface $entity
	 * @param int $limit [optional]
	 *
	 * @return SortInterface|SortInterface[]
	 */
	public function findNextWeight(SortInterface $entity, int $limit = 1)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight > :weight')
			->setParameter('weight', $entity->getWeight())
			->addOrderBy('e.weight', 'ASC');

		// use parent of entity
		if ($entity instanceof TreeInterface && ($parent = $entity->getParent()) !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $entity->getParent());
		}

		// use latest version
		if ($entity instanceof VersionInterface)
		{
			$builder->andWhere('e.version = e.id');
		}

		// ignore deleted
		if ($entity instanceof SoftDeleteInterface)
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		if ($limit === 1)
		{
			try
			{
				return $builder
					->getQuery()
					->getOneOrNullResult();
			}
			catch (NonUniqueResultException $e)
			{
				return null;
			}
		}

		return $builder
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

	/**
	 * Find previous weight
	 *
	 * @param SortInterface $entity
	 * @param int $limit [optional]
	 *
	 * @return SortInterface|SortInterface[]
	 */
	public function findPreviousWeight(SortInterface $entity, int $limit = 1)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight < :weight')
			->setParameter('weight', $entity->getWeight())
			->addOrderBy('e.weight', 'DESC');

		// use parent of entity
		if ($entity instanceof TreeInterface && ($parent = $entity->getParent()) !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $parent);
		}

		// use latest version
		if ($entity instanceof VersionInterface)
		{
			$builder->andWhere('e.version = e.id');
		}

		// ignore deleted
		if ($entity instanceof SoftDeleteInterface)
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		if ($limit === 1)
		{
			try
			{
				return $builder
					->getQuery()
					->getOneOrNullResult();
			}
			catch (NonUniqueResultException $e)
			{
				return null;
			}
		}

		return $builder
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}
}