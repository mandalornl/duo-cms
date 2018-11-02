<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;

class SortSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::prePersist
		];
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof SortInterface || $entity->getWeight() !== null)
		{
			return;
		}

		$entity->setWeight($this->getNextWeight($args));
	}

	/**
	 * Get next weight
	 *
	 * @param LifecycleEventArgs $args
	 *
	 * @return int
	 */
	private function getNextWeight(LifecycleEventArgs $args): int
	{
		$entity = $args->getObject();

		/**
		 * @var EntityManagerInterface $manager
		 */
		$manager = $args->getObjectManager();

		try
		{
			$builder = $manager->createQueryBuilder()
				->select('COALESCE(MAX(e.weight) + 1, 0)')
				->from(get_class($entity), 'e');

			// use parent of entity
			if ($entity instanceof TreeInterface && ($parent = $entity->getParent()) !== null)
			{
				$builder
					->andWhere('e.parent = :parent')
					->setParameter('parent', $parent);
			}

			return (int)$builder
				->getQuery()
				->getOneOrNullResult(Query::HYDRATE_SINGLE_SCALAR);
		}
		catch (NonUniqueResultException $e)
		{
			return 0;
		}
	}
}