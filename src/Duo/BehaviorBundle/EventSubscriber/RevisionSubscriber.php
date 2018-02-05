<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\BehaviorBundle\Entity\RevisionInterface;

class RevisionSubscriber implements EventSubscriber
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

	/**
	 * Load class metadata
	 *
	 * @param LoadClassMetadataEventArgs $args
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args)
	{
		/**
		 * @var ClassMetadata $classMetadata
		 */
		$classMetadata = $args->getClassMetadata();

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null ||
			!$reflectionClass->implementsInterface(RevisionInterface::class))
		{
			return;
		}

		$this->mapRevision($classMetadata, $reflectionClass);
		$this->mapRevisions($classMetadata, $reflectionClass);
	}

	/**
	 * Map revision
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapRevision(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
	{
		if (!$classMetadata->hasAssociation('revision'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'revision',
				'inversedBy'	=> 'revisions',
				'cascade'		=> ['persist', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'joinColumns' 	=> [[
					'name' 					=> 'revision_id',
					'referencedColumnName'	=> 'id',
					'onDelete'				=> 'CASCADE'
				]]
			]);
		}
	}

	/**
	 * Map revision
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapRevisions(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
	{
		if (!$classMetadata->hasAssociation('revisions'))
		{
			$classMetadata->mapOneToMany([
				'fieldName' 	=> 'revisions',
				'mappedBy'		=> 'revision',
				'cascade'		=> ['persist', 'merge', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'orphanRemoval'	=> true,
				'orderBy' => [
					'id' => 'DESC'
				]
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

		if (!$entity instanceof RevisionInterface)
		{
			return;
		}

		$entity->setRevision($entity);
	}
}