<?php

namespace Duo\TranslatorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\TranslatorBundle\Entity\Entry;
use Duo\TranslatorBundle\Entity\EntryTranslation;

class EntrySubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::preUpdate,
			Events::onFlush
		];
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof Entry || $args->hasChangedField('flag'))
		{
			return;
		}

		$entity->setFlag(Entry::FLAG_UPDATED);
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

		foreach ($unitOfWork->getScheduledEntityUpdates() as $entity)
		{
			if (!$entity instanceof EntryTranslation)
			{
				continue;
			}

			/**
			 * @var Entry $translatable
			 */
			$translatable = $entity->getTranslatable();
			$translatable->setFlag(Entry::FLAG_UPDATED);

			$classMetadata = $manager->getClassMetadata(get_class($translatable));

			$unitOfWork->persist($translatable);
			$unitOfWork->computeChangeSet($classMetadata, $translatable);
		}
	}
}