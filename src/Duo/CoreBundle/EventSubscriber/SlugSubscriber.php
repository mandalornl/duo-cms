<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\AdminBundle\Tools\Intl\Slugifier;
use Duo\CoreBundle\Entity\Property\SlugInterface;

class SlugSubscriber implements EventSubscriber
{
	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata,
			Events::prePersist,
			Events::preUpdate
		];
	}

	/**
	 * On load class metadata event
	 *
	 * @param LoadClassMetadataEventArgs $args
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
	{
		$classMetadata = $args->getClassMetadata();

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null ||
			!$reflectionClass->implementsInterface(SlugInterface::class)
		)
		{
			return;
		}

		$name = 'IDX_SLUG';

		if (!(isset($classMetadata->table['indexes'][$name])))
		{
			$classMetadata->table['indexes'][$name] = [
				'columns' => [
					'slug'
				]
			];
		}
	}

    /**
     * On pre persist event
     *
     * @param LifecycleEventArgs $args
	 *
     * @throws \IntlException
     */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$this->setSlug($args->getObject());
	}

    /**
     * On pre update event
     *
     * @param PreUpdateEventArgs $args
	 *
     * @throws \IntlException
     */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$this->setSlug($args->getObject());
	}

	/**
	 * Set slug
	 *
	 * @param object $entity
	 *
	 * @throws \IntlException
	 */
	private function setSlug(object $entity): void
	{
		if (!$entity instanceof SlugInterface)
		{
			return;
		}

		// make sure slug is slugified
		if (!empty($entity->getSlug()))
		{
			$entity->setSlug(Slugifier::slugify($entity->getSlug()));

			return;
		}

		if ($entity->getSlug() === null)
		{
			$entity->setSlug(Slugifier::slugify($entity->getValueToSlugify()));
		}
	}
}
