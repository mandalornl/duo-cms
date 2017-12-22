<?php

namespace Duo\AdminBundle\Repository\Behavior;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Duo\AdminBundle\Entity\Behavior\SortableInterface;
use Duo\AdminBundle\Entity\Behavior\TreeableInterface;

trait SortableTrait
{
	/**
	 * Find next weight
	 *
	 * @param SortableInterface $entity
	 * @param int $limit [optional]
	 *
	 * @return SortableInterface
	 */
	public function findNextWeight(SortableInterface $entity, int $limit = 1)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight > :weight')
			->setParameter('weight', $entity->getWeight())
			->orderBy('e.weight', 'ASC')
			->setMaxResults($limit);

		// use parent of entity
		if ($entity instanceof TreeableInterface && ($parent = $entity->getParent()) !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $entity->getParent());
		}

		// TODO: use latest version of node

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
	 * @param SortableInterface $entity
	 * @param int $limit [optional]
	 *
	 * @return SortableInterface
	 */
	public function findPreviousWeight(SortableInterface $entity, int $limit = 1)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight < :weight')
			->setParameter('weight', $entity->getWeight())
			->orderBy('e.weight', 'DESC')
			->setMaxResults($limit);

		// use parent of entity
		if ($entity instanceof TreeableInterface && ($parent = $entity->getParent()) !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $parent);
		}

		// TODO: use latest version of node

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