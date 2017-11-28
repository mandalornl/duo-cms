<?php

namespace Softmedia\AdminBundle\Listener\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Softmedia\AdminBundle\Entity\Behavior\SortableTrait;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;

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
		$reflectionClass = new \ReflectionClass($entity);

		if (!ReflectionClassHelper::hasTrait($reflectionClass, SortableTrait::class))
		{
			return;
		}

		$property = $reflectionClass->getProperty('weight');
		$property->setAccessible(true);

		if ($property->getValue($entity) !== null)
		{
			return;
		}

		$property->setValue($entity, $this->getNextWeight($args, $reflectionClass));
	}

	/**
	 * Get next weight
	 *
	 * @param LifecycleEventArgs $args
	 * @param \ReflectionClass $reflectionClass
	 *
	 * @return int
	 */
	private function getNextWeight(LifecycleEventArgs $args, \ReflectionClass $reflectionClass): int
	{
		/**
		 * @var EntityManager $entityManager
		 */
		$entityManager = $args->getObjectManager();

		$builder = $entityManager->createQueryBuilder()
			->select('COALESCE(MAX(e.weight) + 1, 0)')
			->from($reflectionClass->getName(), 'e');

		if ($reflectionClass->hasProperty('parent'))
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $reflectionClass->getProperty('parent'));
		}

		return (int)$builder->getQuery()->getSingleScalarResult();
	}
}