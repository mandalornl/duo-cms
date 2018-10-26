<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\CoreBundle\Entity\Property\RevisionInterface as PropertyRevisionInterface;
use Duo\CoreBundle\Entity\RevisionInterface as EntityRevisionInterface;

class RevisionSubscriber implements EventSubscriber
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

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null)
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
	private function mapRevision(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$reflectionClass->implementsInterface(EntityRevisionInterface::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('entity'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'entity',
				'inversedBy' 	=> 'revisions',
				'cascade' 		=> ['persist'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> substr($reflectionClass->getName(), 0, -8),
				'joinColumns' 	=> [[
					'name' 					=> 'entity_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'CASCADE'
				]]
			]);
		}
	}

	/**
	 * Map revisions
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapRevisions(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$reflectionClass->implementsInterface(PropertyRevisionInterface::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('revisions'))
		{
			$classMetadata->mapOneToMany([
				'fieldName' 	=> 'revisions',
				'mappedBy' 		=> 'entity',
				'cascade' 		=> ['persist'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> "{$reflectionClass->getName()}Revision",
				'orphanRemoval' => true,
				'orderBy' 		=> [
					'createdAt' => 'DESC'
				]
			]);
		}
	}
}