<?php

namespace Softmedia\AdminBundle\Listener\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Softmedia\AdminBundle\Entity\Behavior\TimeStampableTrait;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;

final class TimeStampableSubscriber implements EventSubscriber
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
	 * @param $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		$reflectionClass = new \ReflectionClass($entity);

		if (!ReflectionClassHelper::hasTrait($reflectionClass, TimeStampableTrait::class))
		{
			return;
		}

		$property = $reflectionClass->getProperty('createdAt');
		$property->setAccessible(true);

		if (!$property->getValue($entity) instanceof \DateTime)
		{
			$property->setValue($entity, new \DateTime());
		}

		$this->setModifiedAt($entity, $reflectionClass);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();
		$reflectionClass = new \ReflectionClass($entity);

		if (!ReflectionClassHelper::hasTrait($reflectionClass, TimeStampableTrait::class))
		{
			return;
		}

		$this->setModifiedAt($entity, $reflectionClass);
	}

	/**
	 * Set modified at
	 *
	 * @param object $entity
	 * @param \ReflectionClass $reflectionClass
	 */
	private function setModifiedAt($entity, \ReflectionClass $reflectionClass)
	{
		$property = $reflectionClass->getProperty('modifiedAt');
		$property->setAccessible(true);
		$property->setValue($entity, new \DateTime());
	}
}