<?php

namespace Softmedia\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Softmedia\AdminBundle\Entity\Behavior\SluggableInterface;
use Softmedia\AdminBundle\Helper\SlugifyHelper;

final class SluggableSubscriber implements EventSubscriber
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
		if (!$entity instanceof SluggableInterface)
		{
			return;
		}

		$entity->setSlug(SlugifyHelper::slugify($entity->getValueToSlugify()));
	}
}