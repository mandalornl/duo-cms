<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
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

		if (!$classMetadata->hasAssociation('translatable'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'translatable',
				'inversedBy' 	=> 'translations',
				'cascade' 		=> ['persist', 'remove'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> substr($reflectionClass->getName(), 0, -11),
				'joinColumns' 	=> [[
					'name' => 'translatable_id',
					'referencedColumnName' => 'id',
					'onDelete' => 'CASCADE'
				]]
			]);
		}

		$name = 'UNIQ_TRANSLATION';

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