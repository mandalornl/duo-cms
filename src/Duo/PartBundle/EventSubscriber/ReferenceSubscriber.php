<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityRepository;
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

		/**
		 * @var EntityRepository $repository
		 */
		$repository = $args->getObjectManager()->getRepository($className);

		$repository->createQueryBuilder('e')
			->update()
			->set('e.entityId', ':entityId')
			->set('e.partId', ':partId')
			->where('e = :reference')
			->setParameter('entityId', $entity->getEntity()->getId())
			->setParameter('partId', $entity->getId())
			->setParameter('reference', $entity->getReference())
			->getQuery()
			->execute();
	}
}