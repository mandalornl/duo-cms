<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\TranslationInterface;

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
	 * On load class metadata
	 *
	 * @param LoadClassMetadataEventArgs $args
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
	{
		/**
		 * @var ClassMetadata $classMetadata
		 */
		$classMetadata = $args->getClassMetadata();

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null)
		{
			return;
		}

		$this->mapTranslatable($classMetadata, $reflectionClass);
		$this->mapTranslation($classMetadata, $reflectionClass);
	}

	/**
	 * Map translatable
	 *
	 * @param ClassMetadata $classMetaData
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapTranslatable(ClassMetadata $classMetaData, \ReflectionClass $reflectionClass): void
	{
		if (!$reflectionClass->implementsInterface(TranslateInterface::class))
		{
			return;
		}

		if (!$classMetaData->hasAssociation('translations'))
		{
			$classMetaData->mapOneToMany([
				'fieldName' 	=> 'translations',
				'mappedBy' 		=> 'translatable',
				'indexBy' 		=> 'locale',
				'cascade' 		=> ['persist', 'merge', 'remove'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> "{$reflectionClass->getName()}Translation",
				'orphanRemoval' => true
			]);
		}
	}

	/**
	 * Map translation
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapTranslation(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$reflectionClass->implementsInterface(TranslationInterface::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('translatable'))
		{
			$targetEntity = substr($reflectionClass->getName(), 0, -11);

			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'translatable',
				'inversedBy' 	=> 'translations',
				'cascade' 		=> ['persist', 'remove'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> $targetEntity,
				'joinColumns' 	=> [[
					'name' 					=> 'translatable_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'CASCADE'
				]]
			]);
		}

		$name = 'translation_uniq';

		if (!isset($classMetadata->table['uniqueConstraints'][$name]))
		{
			$classMetadata->table['uniqueConstraints'][$name] = [
				'columns' => [
					'translatable_id',
					'locale'
				]
			];
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