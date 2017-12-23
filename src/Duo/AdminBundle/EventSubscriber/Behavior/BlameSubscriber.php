<?php

namespace Duo\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\AdminBundle\Entity\Behavior\BlameTrait;
use Duo\AdminBundle\Helper\ReflectionClassHelper;

final class BlameSubscriber implements EventSubscriber
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

		if ($classMetadata->getReflectionClass() === null)
		{
			return;
		}

		$this->mapField($classMetadata);
	}

	/**
	 * Map field
	 *
	 * @param ClassMetadata $classMetadata
	 */
	private function mapField(ClassMetadata $classMetadata)
	{
		$reflectionClass = $classMetadata->getReflectionClass();

		if (!ReflectionClassHelper::hasTrait($reflectionClass, BlameTrait::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('createdBy'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'createdBy',
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> $reflectionClass->getMethod('getBlameableUserEntityClassName')->invoke(null),
				'joinColumn' 	=> [[
					'name' 					=> 'created_by_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'SET NULL'
				]]
			]);
		}

		if (!$classMetadata->hasAssociation('modifiedBy'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'modifiedBy',
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> $reflectionClass->getMethod('getBlameableUserEntityClassName')->invoke(null),
				'joinColumn' 	=> [[
					'name' 					=> 'modified_by_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'SET NULL'
				]]
			]);
		}

		if (!$classMetadata->hasAssociation('deletedBy'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'deletedBy',
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> $reflectionClass->getMethod('getBlameableUserEntityClassName')->invoke(null),
				'joinColumn' 	=> [[
					'name' 					=> 'deleted_by_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'SET NULL'
				]]
			]);
		}
	}
}