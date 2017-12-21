<?php

namespace Softmedia\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Softmedia\AdminBundle\Entity\Behavior\SortableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableInterface;

final class SortableSubscriber implements EventSubscriber
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
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof SortableInterface || $entity->getWeight() !== null)
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
		$reflectionClass = new \ReflectionClass($entity);

		/**
		 * @var EntityManager $entityManager
		 */
		$entityManager = $args->getObjectManager();

		$builder = $entityManager->createQueryBuilder()
			->select('COALESCE(MAX(e.weight) + 1, 0)')
			->from($reflectionClass->getName(), 'e');

		// use parent of entity
		if ($entity instanceof TreeableInterface && ($parent = $entity->getParent()) !== null)
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