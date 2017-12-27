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
		$entity = $args->getObject();

		if (!$entity instanceof UrlInterface)
		{
			return;
		}

		$this->setUrl($entity);
		$this->setTranslationUrl($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof UrlInterface)
		{
			return;
		}

		$this->setUrl($entity);
		$this->setTranslationUrl($entity);
	}

	/**
	 * Set url
	 *
	 * @param UrlInterface $entity
	 */
	private function setUrl(UrlInterface $entity)
	{
		if (!$entity instanceof TreeInterface)
		{
			return;
		}

		$urls = [$entity->getValueToUrlize()];

		/**
		 * @var UrlInterface|TreeInterface $parent
		 */
		$parent = $entity->getParent();

		while ($parent !== null)
		{
			$urls[] = $parent->getValueToUrlize();

			$parent = $parent->getParent();
		}

		$entity->setUrl(implode('/', array_reverse($urls)));
	}

	/**
	 * Set translation url
	 *
	 * @param UrlInterface $entity
	 */
	private function setTranslationUrl(UrlInterface $entity)
	{
		if (!$entity instanceof TranslationInterface)
		{
			return;
		}

		/**
		 * @var TreeInterface $translatable
		 */
		$translatable = $entity->getTranslatable();

		if (!$translatable instanceof TreeInterface)
		{
			return;
		}

		$urls = [$entity->getValueToUrlize()];

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

		$entity->setUrl(implode('/', array_reverse($urls)));
	}
}