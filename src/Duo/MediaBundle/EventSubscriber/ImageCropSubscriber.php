<?php

namespace Duo\MediaBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
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
			Events::preUpdate
		];
	}

	/**
	 * On post update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof ImageCrop || !$args->hasChangedField('media'))
		{
			return;
		}

		if ($args->getNewValue('media') === null)
		{
			$manager = $args->getEntityManager();

			$classMetadata = $manager->getClassMetadata(get_class($entity));

			$unitOfWork = $manager->getUnitOfWork();
			$unitOfWork->remove($entity);
			$unitOfWork->computeChangeSet($classMetadata, $entity);
		}
	}
}