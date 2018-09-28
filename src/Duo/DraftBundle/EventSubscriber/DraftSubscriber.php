<?php

namespace Duo\DraftBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

use Duo\DraftBundle\Entity\DraftInterface as EntityDraftInterface;
use Duo\DraftBundle\Entity\Property\DraftInterface as PropertyDraftInterface;

class DraftSubscriber implements EventSubscriber
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

		$this->mapDraft($classMetadata, $reflectionClass);
		$this->mapDrafts($classMetadata, $reflectionClass);
	}

	/**
	 * Map draft
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapDraft(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$reflectionClass->implementsInterface(EntityDraftInterface::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('entity'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' => 'entity',
				'inversedBy' => 'drafts',
				'cascade' => ['persist'],
				'fetch' => ClassMetadata::FETCH_LAZY,
				'targetEntity' => substr($reflectionClass->getName(), 0, -5),
				'joinColumns' => [[
					'name' => 'entity_id',
					'referencedColumnName' => 'id',
					'onDelete' => 'CASCADE'
				]]
			]);
		}
	}

	/**
	 * Map drafts
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapDrafts(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$reflectionClass->implementsInterface(PropertyDraftInterface::class))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('drafts'))
		{
			$classMetadata->mapOneToMany([
				'fieldName' => 'drafts',
				'mappedBy' => 'entity',
				'cascade' => ['persist'],
				'fetch' => ClassMetadata::FETCH_LAZY,
				'targetEntity' => "{$reflectionClass->getName()}Draft",
				'orphanRemoval' => true,
				'orderBy' => [
					'id' => 'DESC'
				]
			]);
		}
	}
}