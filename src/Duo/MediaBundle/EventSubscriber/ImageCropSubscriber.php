<?php

namespace Duo\MediaBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Duo\MediaBundle\Entity\ImageCrop;

class ImageCropSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::onFlush
		];
	}

	/**
	 * On flush event
	 *
	 * @param OnFlushEventArgs $args
	 */
	public function onFlush(OnFlushEventArgs $args): void
	{
		$unitOfWork = $args->getEntityManager()->getUnitOfWork();

		foreach ($unitOfWork->getScheduledEntityUpdates() as $entity)
		{
			if (!$entity instanceof ImageCrop)
			{
				continue;
			}

			$changeSet = $unitOfWork->getEntityChangeSet($entity);

			// remove crop if no media is selected
			if (isset($changeSet['media']) && $changeSet['media'][1] === null)
			{
				$unitOfWork->remove($entity);
			}
		}

		$unitOfWork->computeChangeSets();
	}
}