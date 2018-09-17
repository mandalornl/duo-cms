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
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * PartSubscriber constructor
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::postLoad,
			Events::postPersist,
			Events::postUpdate,
			Events::onFlush
		];
	}

	/**
	 * On post load event
	 *
	 * @param LifecycleEventArgs $args
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

		$reflectionClass = $args->getObjectManager()->getClassMetadata(get_class($entity))->getReflectionClass();

		$property = $reflectionClass->getProperty('parts');
		$property->setAccessible(true);
		$property->setValue($entity, new PartCollection($manager, $entity));
	}

	/**
	 * On post persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface || !count($entity->getParts()))
		{
			return;
		}

		$manager = $args->getObjectManager();

		foreach ($entity->getParts() as $part)
		{
			$manager->persist($part);
		}

		$manager->flush();
	}

	/**
	 * On post update event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface)
		{
			return;
		}

		$collection = $entity->getParts();

		if ($collection instanceof PartCollection && !$collection->isDirty())
		{
			return;
		}

		$manager = $args->getObjectManager();

		foreach ($collection->getDeleteDiff() as $part)
		{
			$manager->remove($part);
		}

		foreach ($collection->getInsertDiff() as $part)
		{
			$manager->persist($part);
		}

		$manager->flush();
	}

	/**
	 * On flush event
	 *
	 * @param OnFlushEventArgs $args
	 */
	public function onFlush(OnFlushEventArgs $args): void
	{
		$manager = $args->getEntityManager();

		$unitOfWork = $manager->getUnitOfWork();

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
	}
}