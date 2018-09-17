<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
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

		$reference = $entity->getReference();
		$reference->setEntityId($entity->getEntity()->getId());
		$reference->setPartId($entity->getId());

		$manager = $args->getObjectManager();
		$manager->persist($reference);
		$manager->flush();
	}
}