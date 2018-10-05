<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;

class ReferenceSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::postPersist
		];
	}

	/**
	 * On post persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface)
		{
			return;
		}

		$className = get_class($entity->getReference());

		$dql = <<<SQL
UPDATE {$className} e SET e.entityId = :entityId, e.partId = :partId WHERE e = :reference
SQL;

		/**
		 * @var EntityManagerInterface $manager
		 */
		$manager = $args->getObjectManager();

		$manager->createQuery($dql)
			->setParameter('entityId', $entity->getEntity()->getId())
			->setParameter('partId', $entity->getId())
			->setParameter('reference', $entity->getReference())
			->execute();
	}
}