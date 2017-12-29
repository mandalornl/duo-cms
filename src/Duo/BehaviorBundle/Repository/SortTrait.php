<?php

namespace Duo\BehaviorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
	 * @return SortInterface
	 */
	public function findNextWeight(SortInterface $entity, int $limit = 1)
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
				->setParameter('parent', $entity->getParent());
		}

		// use latest version
		if ($entity instanceof VersionInterface)
		{
			$builder->andWhere('e.version = e.id');
		}

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

	/**
	 * Find previous weight
	 *
	 * @param SortInterface $entity
	 * @param int $limit [optional]
	 *
	 * @return SortInterface
	 */
	public function findPreviousWeight(SortInterface $entity, int $limit = 1)
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

		// use latest version
		if ($entity instanceof VersionInterface)
		{
			$builder->andWhere('e.version = e.id');
		}

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
}