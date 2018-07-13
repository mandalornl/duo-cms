<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\CoreBundle\Entity\TranslationInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Entity\UrlInterface;

class UrlSubscriber implements EventSubscriber
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
	public function prePersist(LifecycleEventArgs $args): void
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
	public function preUpdate(PreUpdateEventArgs $args): void
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
	private function setUrl(UrlInterface $entity): void
	{
		$urls = [ $entity->getValueToUrlize() ];

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

		$entity->setUrl($this->generateUrl($urls));
	}

	/**
	 * Set translation url
	 *
	 * @param UrlInterface $entity
	 */
	private function setTranslationUrl(UrlInterface $entity): void
	{
		if (!$entity instanceof TranslationInterface)
		{
			return;
		}

		/**
		 * @var TreeInterface $translatable
		 */
		$translatable = $entity->getTranslatable();

		$urls = [ $entity->getValueToUrlize() ];

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

		$entity->setUrl($this->generateUrl($urls));
	}

	/**
	 * Generate url
	 *
	 * @param array $urls
	 *
	 * @return string
	 */
	private function generateUrl(array $urls): string
	{
		return '/' . implode('/', array_reverse(array_filter($urls)));
	}
}