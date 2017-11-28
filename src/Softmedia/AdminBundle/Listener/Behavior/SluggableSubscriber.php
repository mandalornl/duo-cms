<?php

namespace Softmedia\AdminBundle\Listener\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Softmedia\AdminBundle\Entity\Behavior\SluggableTrait;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;
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
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$this->setSlug($args->getObject());
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setSlug($args->getObject());
	}

	/**
	 * Set slug
	 *
	 * @param object $entity
	 * @param \ReflectionClass $reflectionClass
	 */
	private function setSlug($entity, \ReflectionClass $reflectionClass = null)
	{
		$reflectionClass = $reflectionClass ?: new \ReflectionClass($entity);

		if (!ReflectionClassHelper::hasTrait($reflectionClass, SluggableTrait::class))
		{
			return;
		}

		$property = $reflectionClass->getProperty('slug');
		$property->setAccessible(true);

		/**
		 * @var SluggableTrait $entity
		 */
		$property->setValue($entity, SlugifyHelper::slugify($entity->getValueToSlugify()));
	}
}