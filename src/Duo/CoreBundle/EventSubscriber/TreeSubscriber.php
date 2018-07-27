<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Entity\UrlInterface;

class TreeSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata,
			Events::onFlush
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
			!$reflectionClass->implementsInterface(TreeInterface::class))
		{
			return;
		}

		$this->mapParent($classMetadata, $reflectionClass);
		$this->mapChildren($classMetadata, $reflectionClass);
	}

	/**
	 * On flush event
	 *
	 * @param OnFlushEventArgs $args
	 */
	public function onFlush(OnFlushEventArgs $args): void
	{
		$unitOfWork = $args->getEntityManager()->getUnitOfWork();

		foreach ($unitOfWork->getScheduledEntityUpdates() as $entity)
		{
			if (!$entity instanceof TreeInterface)
			{
				continue;
			}

			$changeSet = $unitOfWork->getEntityChangeSet($entity);

			if (!isset($changeSet['parent']))
			{
				continue;
			}

			// unset to recompute url's
			if ($entity instanceof UrlInterface)
			{
				$this->unsetUrl($entity, $unitOfWork);
			}
			else
			{
				if ($entity instanceof TranslateInterface)
				{
					$this->unsetTranslationUrl($entity, $unitOfWork);
				}
			}
		}

		$unitOfWork->computeChangeSets();
	}

	/**
	 * Map parent
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapParent(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$classMetadata->hasAssociation('parent'))
		{
			$classMetadata->mapManyToOne([
				'fieldName'		=> 'parent',
				'inversedBy'	=> 'children',
				'cascade'		=> ['persist', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'joinColumns'	=> [[
					'name'					=> 'parent_id',
					'referencedColumnName'	=> 'id',
					'onDelete'				=> 'CASCADE'
				]]
			]);
		}
	}

	/**
	 * Map children
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapChildren(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass): void
	{
		if (!$classMetadata->hasAssociation('children'))
		{
			$mapping = [
				'fieldName'		=> 'children',
				'mappedBy'		=> 'parent',
				'cascade'		=> ['persist', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName()
			];

			// order by weight
			if ($classMetadata->hasField('weight'))
			{
				$mapping['orderBy'] = [
					'weight' => 'ASC'
				];
			}

			$classMetadata->mapOneToMany($mapping);
		}
	}

	/**
	 * Unset url
	 *
	 * @param TreeInterface|UrlInterface $entity
	 * @param UnitOfWork $unitOfWork
	 */
	private function unsetUrl(UrlInterface $entity, UnitOfWork $unitOfWork): void
	{
		$entity->setUrl(null);

		$unitOfWork->persist($entity);

		$this->unsetChildrenUrl($entity, $unitOfWork);
	}

	/**
	 * Unset children url
	 *
	 * @param TreeInterface $entity
	 * @param UnitOfWork $unitOfWork
	 */
	private function unsetChildrenUrl(TreeInterface $entity, UnitOfWork $unitOfWork): void
	{
		/**
		 * @var TreeInterface|UrlInterface $child
		 */
		foreach ($entity->getChildren() as $child)
		{
			$child->setUrl(null);

			$unitOfWork->persist($child);

			$this->unsetChildrenUrl($child, $unitOfWork);
		}
	}

	/**
	 * Unset translation url
	 *
	 * @param TranslateInterface $entity
	 * @param UnitOfWork $unitOfWork
	 */
	private function unsetTranslationUrl(TranslateInterface $entity, UnitOfWork $unitOfWork): void
	{
		foreach ($entity->getTranslations() as $translation)
		{
			if (!$translation instanceof UrlInterface)
			{
				return;
			}

			$translation->setUrl(null);

			$unitOfWork->persist($translation);
		}

		$this->unsetTranslationChildrenUrl($entity, $unitOfWork);
	}

	/**
	 * Unset translation children url
	 *
	 * @param TreeInterface|TranslateInterface $entity
	 * @param UnitOfWork $unitOfWork
	 */
	private function unsetTranslationChildrenUrl(TreeInterface $entity, UnitOfWork $unitOfWork): void
	{
		foreach ($entity->getChildren() as $child)
		{
			foreach ($child->getTranslations() as $translation)
			{
				if (!$translation instanceof UrlInterface)
				{
					return;
				}

				$translation->setUrl(null);

				$unitOfWork->persist($translation);
			}

			$this->unsetTranslationChildrenUrl($child, $unitOfWork);
		}
	}
}