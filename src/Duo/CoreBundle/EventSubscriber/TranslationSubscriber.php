<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\AdminBundle\Tools\ORM as Tools;
use Duo\CoreBundle\Entity\Property\TranslationInterface;

class TranslationSubscriber implements EventSubscriber
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
	 * On load class metadata event
	 *
	 * @param LoadClassMetadataEventArgs $args
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
	{
		$classMetadata = $args->getClassMetadata();

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null ||
			!$reflectionClass->implementsInterface(TranslationInterface::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('entity'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'entity',
				'inversedBy' 	=> 'translations',
				'cascade' 		=> ['persist', 'remove'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> Tools\ClassMetadata::getManyToOneTargetEntity($reflectionClass, 'Translation'),
				'joinColumns' 	=> [[
					'name' 					=> 'entity_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'CASCADE'
				]]
			]);
		}

		$name = 'UNIQ_TRANSLATION';

		if (!isset($classMetadata->table['uniqueConstraints'][$name]))
		{
			$classMetadata->table['uniqueConstraints'][$name] = [
				'columns' => [
					'entity_id',
					'locale'
				]
			];
		}
	}
}
