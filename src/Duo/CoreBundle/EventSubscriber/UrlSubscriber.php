<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TranslationInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Entity\Property\UrlInterface;

class UrlSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
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
			!$reflectionClass->implementsInterface(UrlInterface::class)
		)
		{
			return;
		}

		$name = 'IDX_URL';

		if (!(isset($classMetadata->table['indexes'][$name])))
		{
			$classMetadata->table['indexes'][$name] = [
				'columns' => [
					'url'
				]
			];
		}
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

		if ($entity instanceof TranslationInterface)
		{
			$this->setTranslationUrl($entity);

			return;
		}

		$this->setUrl($entity);
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

		if ($entity instanceof TranslationInterface)
		{
			$this->setTranslationUrl($entity);

			return;
		}

		$this->setUrl($entity);
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
	 * @param UrlInterface|TranslationInterface $entity
	 */
	private function setTranslationUrl(UrlInterface $entity): void
	{
		$urls = [ $entity->getValueToUrlize() ];

		$translatable = $entity->getEntity();

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
		return implode('/', array_reverse(array_filter($urls)));
	}
}