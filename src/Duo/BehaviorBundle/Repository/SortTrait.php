<?php

namespace Duo\BehaviorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\RevisionInterface;

trait SortTrait
{
	/**
	 * Find previous entity using weight
	 *
	 * @param SortInterface $entity
	 * @param int $limit [optional]
	 *
	 * @return SortInterface|SortInterface[]
	 */
	public function findPreviousEntityUsingWeight(SortInterface $entity, int $limit = 1)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight < :weight')
			->setParameter('weight', $entity->getWeight())
			->addOrderBy('e.weight', 'DESC')
			->setMaxResults($limit);

		// use parent of entity
		if ($entity instanceof TreeInterface && ($parent = $entity->getParent()) !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $parent);
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
			->getQuery()
			->getResult();
	}

	/**
	 * Find next entity using weight
	 *
	 * @param SortInterface $entity
	 * @param int $limit [optional]
	 *
	 * @return SortInterface|SortInterface[]
	 */
	public function findNextEntityUsingWeight(SortInterface $entity, int $limit = 1)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight > :weight')
			->setParameter('weight', $entity->getWeight())
			->addOrderBy('e.weight', 'ASC')
			->setMaxResults($limit);

		// use parent of entity
		if ($entity instanceof TreeInterface && ($parent = $entity->getParent()) !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $parent);
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
			->getQuery()
			->getResult();
	}
}