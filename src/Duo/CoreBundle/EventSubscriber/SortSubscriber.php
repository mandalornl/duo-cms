<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Duo\CoreBundle\Entity\SortInterface;
use Duo\CoreBundle\Entity\TreeInterface;

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
		 * @var EntityManager $entityManager
		 */
		$entityManager = $args->getObjectManager();

		$builder = $entityManager->createQueryBuilder()
			->select('COALESCE(MAX(e.weight) + 1, 0)')
			->from(ClassUtils::getClass($entity), 'e');

		// use parent of entity
		if ($entity instanceof TreeInterface && ($parent = $entity->getParent()) !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $parent);
		}

		try
		{
			return (int)$builder->getQuery()->getSingleScalarResult();
		}
		catch (NonUniqueResultException | NoResultException $e)
		{
			return 0;
		}
	}
}