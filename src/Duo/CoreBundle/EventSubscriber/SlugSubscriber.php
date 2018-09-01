<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\AdminBundle\Tools\Intl\Slugifier;
use Duo\CoreBundle\Entity\Property\SlugInterface;

class SlugSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::prePersist,
			Events::preUpdate
		];
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
	 * @param mixed $entity
	 *
	 * @throws \IntlException
	 */
	private function setSlug($entity): void
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