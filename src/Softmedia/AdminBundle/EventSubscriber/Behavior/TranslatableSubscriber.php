<?php

namespace Softmedia\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Softmedia\AdminBundle\Entity\Behavior\TranslatableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TranslationTrait;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;

final class TranslatableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata
		];
	}

	/**
	 * On load class metadata
	 *
	 * @param LoadClassMetadataEventArgs $args
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args)
	{
		/**
		 * @var ClassMetadata $classMetadata
		 */
		$classMetadata = $args->getClassMetadata();

		if (!$classMetadata->getReflectionClass())
		{
			return;
		}

		$this->mapTranslatable($classMetadata);
		$this->mapTranslation($classMetadata);
	}

	/**
	 * Map translatable
	 *
	 * @param ClassMetadata $classMetaData
	 */
	private function mapTranslatable(ClassMetadata $classMetaData)
	{
		$reflectionClass = $classMetaData->getReflectionClass();

		if (!ReflectionClassHelper::hasTrait($reflectionClass, TranslatableTrait::class))
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
	 */
	private function mapTranslation(ClassMetadata $classMetadata)
	{
		$reflectionClass = $classMetadata->getReflectionClass();

		if (!ReflectionClassHelper::hasTrait($reflectionClass, TranslationTrait::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('translatable'))
		{
			$targetEntity = substr($reflectionClass->getName(), 0, -11);

			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'translatable',
				'inversedBy' 	=> 'translations',
				'cascade' 		=> ['persist', 'merge'],
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
}