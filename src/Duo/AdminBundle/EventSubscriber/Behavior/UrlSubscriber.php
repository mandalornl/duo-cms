<?php

namespace Duo\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\AdminBundle\Entity\Behavior\TranslateInterface;
use Duo\AdminBundle\Entity\Behavior\TranslationInterface;
use Duo\AdminBundle\Entity\Behavior\TreeInterface;
use Duo\AdminBundle\Entity\Behavior\UrlInterface;

final class UrlSubscriber implements EventSubscriber
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
	 *
	 * @param object $entity
	 */
	private function setUrl($entity)
	{
		if (!$entity instanceof UrlInterface)
		{
			return;
		}

		$urls = [$entity->getValueToUrlize()];

		if ($entity instanceof TreeInterface)
		{
			/**
			 * @var UrlInterface|TreeInterface $parent
			 */
			$parent = $entity->getParent();

			while ($parent !== null)
			{
				$urls[] = $parent->getValueToUrlize();

				$parent = $parent->getParent();
			}
		}
		else
		{
			if ($entity instanceof TranslationInterface)
			{
				$translatable = $entity->getTranslatable();

				if ($translatable instanceof TreeInterface)
				{
					/**
					 * @var TranslateInterface|TreeInterface $parent
					 */
					$parent = $translatable->getParent();

					while ($parent !== null)
					{
						/**
						 * @var UrlInterface $translation
						 */
						$translation = $parent->translate($entity->getLocale());

						$urls[] = $translation->getValueToUrlize();

						$parent = $parent->getParent();
					}
				}
			}
		}

		$entity->setUrl(implode('/', array_reverse($urls)));
	}
}