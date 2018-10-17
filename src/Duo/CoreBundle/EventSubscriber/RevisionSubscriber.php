<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\CoreBundle\Entity\Property\RevisionInterface;

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
	public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
	{
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
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof RevisionInterface)
		{
			return;
		}

		$entity->setRevision($entity);
	}

	/**
	 * Map revision
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapRevision(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$classMetadata->hasAssociation('revision'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'revision',
				'inversedBy'	=> 'revisions',
				'cascade'		=> ['persist'],
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
	private function mapRevisions(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$classMetadata->hasAssociation('revisions'))
		{
			$classMetadata->mapOneToMany([
				'fieldName' 	=> 'revisions',
				'mappedBy'		=> 'revision',
				'cascade'		=> ['persist'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'orderBy' => [
					'id' => 'DESC'
				]
			]);
		}
	}
}