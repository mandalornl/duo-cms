<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Duo\PartBundle\Collection\PartCollection;
use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;

class PartSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::postLoad,
			Events::onFlush
		];
	}

	/**
	 * On post load event
	 *
	 * @param LifecycleEventArgs $args
	 *
	 * @throws \ReflectionException
	 */
	public function postLoad(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface)
		{
			return;
		}

		/**
		 * @var EntityManagerInterface $manager
		 */
		$manager = $args->getObjectManager();

		$reflectionClass = $manager->getClassMetadata(get_class($entity))->getReflectionClass();

		$property = $reflectionClass->getProperty('parts');
		$property->setAccessible(true);
		$property->setValue($entity, new PartCollection($manager, $entity));
	}

	/**
	 * On flush event
	 *
	 * @param OnFlushEventArgs $args
	 */
	public function onFlush(OnFlushEventArgs $args): void
	{
		$unitOfWork = $args->getEntityManager()->getUnitOfWork();

		foreach ($unitOfWork->getScheduledEntityInsertions() as $entity)
		{
			if (!$entity instanceof PropertyPartInterface)
			{
				continue;
			}

			foreach ($entity->getParts() as $part)
			{
				$unitOfWork->persist($part);
			}
		}

		foreach ($unitOfWork->getScheduledEntityUpdates() as $entity)
		{
			if (!$entity instanceof PropertyPartInterface)
			{
				continue;
			}

			$collection = $entity->getParts();

			if (!($collection instanceof PartCollection && $collection->isDirty()))
			{
				continue;
			}

			foreach ($collection->getDeleteDiff() as $part)
			{
				$unitOfWork->remove($part);
			}

			foreach ($collection->getInsertDiff() as $part)
			{
				$unitOfWork->persist($part);
			}
		}

		foreach ($unitOfWork->getScheduledEntityDeletions() as $entity)
		{
			if (!$entity instanceof PropertyPartInterface)
			{
				continue;
			}

			foreach ($entity->getParts() as $part)
			{
				$unitOfWork->remove($part);
			}
		}

		$unitOfWork->computeChangeSets();
	}
}
