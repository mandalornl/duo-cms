<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\AdminBundle\Tools\ORM as Tools;
use Duo\CoreBundle\Entity\Property\TranslateInterface;

class TranslateSubscriber implements EventSubscriber
{
	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * TranslateSubscriber constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata,
			Events::postLoad,
			Events::prePersist
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
			!$reflectionClass->implementsInterface(TranslateInterface::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('translations'))
		{
			$classMetadata->mapOneToMany([
				'fieldName' 	=> 'translations',
				'mappedBy' 		=> 'entity',
				'indexBy' 		=> 'locale',
				'cascade' 		=> ['persist', 'remove'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> Tools\ClassMetadata::getOneToManyTargetEntity($reflectionClass, 'Translation'),
				'orphanRemoval' => true
			]);
		}
	}

	/**
	 * On post load event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args): void
	{
		$this->setLocale($args);
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$this->setLocale($args);
	}

	/**
	 * Set locale
	 *
	 * @param LifecycleEventArgs $args
	 */
	private function setLocale(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof TranslateInterface)
		{
			return;
		}

		$entity
			->setDefaultLocale($this->localeHelper->getDefaultLocale())
			->setCurrentLocale($this->localeHelper->getLocale());
	}
}
