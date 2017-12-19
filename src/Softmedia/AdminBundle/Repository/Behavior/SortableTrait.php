<?php

namespace Softmedia\AdminBundle\Repository\Behavior;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Softmedia\AdminBundle\Entity\Behavior\SortableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableInterface;

trait SortableTrait
{
	/**
	 * Find next weight
	 *
	 * @param SortableInterface $entity
	 *
	 * @return SortableInterface
	 */
	public function findNextWeight(SortableInterface $entity)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight > :weight')
			->setParameter('weight', $entity->getWeight())
			->setMaxResults(1);

		// use parent of entity
		if ($entity instanceof TreeableInterface)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $entity->getParent());
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
	 * @param SortableInterface $entity
	 *
	 * @return SortableInterface
	 */
	public function findPreviousWeight(SortableInterface $entity)
	{
		/**
		 * @var EntityRepository $this
		 */
		$builder = $this->createQueryBuilder('e')
			->where('e.weight < :weight')
			->setParameter('weight', $entity->getWeight())
			->orderBy('e.weight', 'DESC')
			->setMaxResults(1);

		// use parent of entity
		if ($entity instanceof TreeableInterface)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $entity->getParent());
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