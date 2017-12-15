<?php

namespace Softmedia\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Softmedia\AdminBundle\Entity\Behavior\TreeableInterface;
use Softmedia\AdminBundle\Entity\Behavior\UrlableInterface;

class UrlableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
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
		$this->setUrl($args->getObject());
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setUrl($args->getObject());
	}

	/**
	 * Set url
	 * @param object $entity
	 */
	private function setUrl($entity)
	{
		if (!$entity instanceof UrlableInterface)
		{
			return;
		}

		if ($entity instanceof TreeableInterface)
		{
			$urls = [$entity->getValueToUrlize()];

			/**
			 * @var UrlableInterface|TreeableInterface $parent
			 */
			$parent = $entity->getParent();

			while ($parent !== null)
			{
				$urls[] = $parent->getValueToUrlize();

				$parent = $parent->getParent();
			}

			$entity->setUrl(implode('/', array_reverse($urls)) . '/');
			return;
		}

		$entity->setUrl($entity->getValueToUrlize() . '/');
	}
}