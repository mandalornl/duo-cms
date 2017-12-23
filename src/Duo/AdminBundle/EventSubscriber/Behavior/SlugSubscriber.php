<?php

namespace Duo\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\AdminBundle\Entity\Behavior\SlugInterface;
use Duo\AdminBundle\Helper\SlugifyHelper;

final class SlugSubscriber implements EventSubscriber
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
     * @throws \IntlException
     */
	public function prePersist(LifecycleEventArgs $args)
	{
		$this->setSlug($args->getObject());
	}

    /**
     * On pre update event
     *
     * @param PreUpdateEventArgs $args
     * @throws \IntlException
     */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setSlug($args->getObject());
	}

	/**
	 * Set slug
	 *
	 * @param object $entity
     * @throws \IntlException
     */
	private function setSlug($entity)
	{
		if (!$entity instanceof SlugInterface)
		{
			return;
		}

		$entity->setSlug(SlugifyHelper::slugify($entity->getValueToSlugify()));
	}
}