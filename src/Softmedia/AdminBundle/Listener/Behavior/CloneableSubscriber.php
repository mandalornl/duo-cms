<?php

namespace Softmedia\AdminBundle\Listener\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Softmedia\AdminBundle\Entity\Behavior\CloneableTrait;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;

final class CloneableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata,
			Events::prePersist
		];
	}

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

		$reflectionClass = $classMetadata->getReflectionClass();

		if (!ReflectionClassHelper::hasTrait($reflectionClass, CloneableTrait::class))
		{
			return;
		}

		$this->mapVersion($classMetadata, $reflectionClass);
		$this->mapVersions($classMetadata, $reflectionClass);
	}

	/**
	 * Map version
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapVersion(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
	{
		if (!$classMetadata->hasAssociation('version'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'version',
				'inversedBy'	=> 'versions',
				'cascade'		=> ['persist', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'joinColumns' 	=> [[
					'name' 					=> 'version_id',
					'referencedColumnName'	=> 'id',
					'onDelete'				=> 'CASCADE'
				]]
			]);
		}
	}

	/**
	 * Map versions
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapVersions(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
	{
		if (!$classMetadata->hasAssociation('versions'))
		{
			$classMetadata->mapOneToMany([
				'fieldName' 	=> 'versions',
				'mappedBy'		=> 'version',
				'cascade'		=> ['persist', 'merge', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'orphanRemoval'	=> true
			]);
		}
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		$reflectionClass = new \ReflectionClass($entity);

		if (!ReflectionClassHelper::hasTrait($reflectionClass, CloneableTrait::class))
		{
			return;
		}

		$property = $reflectionClass->getProperty('version');
		$property->setAccessible(true);

		if ($property->getValue($entity) !== null)
		{
			return;
		}

		$property->setValue($entity, $entity);
	}
}