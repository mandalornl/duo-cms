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
		$uow = $args->getEntityManager()->getUnitOfWork();

		foreach ($uow->getScheduledEntityUpdates() as $entity)
		{
			if (!$entity instanceof TreeInterface)
			{
				continue;
			}

			$changeSet = $uow->getEntityChangeSet($entity);

			if (!isset($changeSet['parent']))
			{
				continue;
			}

			// unset to recompute url's
			if ($entity instanceof UrlInterface)
			{
				$this->unsetUrl($entity, $uow);
			}
			else
			{
				if ($entity instanceof TranslateInterface)
				{
					$this->unsetTranslationUrl($entity, $uow);
				}
			}
		}

		$uow->computeChangeSets();
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
	 * @param UnitOfWork $uow
	 */
	private function unsetUrl(UrlInterface $entity, UnitOfWork $uow): void
	{
		$entity->setUrl(null);

		$uow->persist($entity);

		$this->unsetChildrenUrl($entity, $uow);
	}

	/**
	 * Unset children url
	 *
	 * @param TreeInterface $entity
	 * @param UnitOfWork $uow
	 */
	private function unsetChildrenUrl(TreeInterface $entity, UnitOfWork $uow): void
	{
		/**
		 * @var TreeInterface|UrlInterface $child
		 */
		foreach ($entity->getChildren() as $child)
		{
			$child->setUrl(null);

			$uow->persist($child);

			$this->unsetChildrenUrl($child, $uow);
		}
	}

	/**
	 * Unset translation url
	 *
	 * @param TranslateInterface $entity
	 * @param UnitOfWork $uow
	 */
	private function unsetTranslationUrl(TranslateInterface $entity, UnitOfWork $uow): void
	{
		foreach ($entity->getTranslations() as $translation)
		{
			if (!$translation instanceof UrlInterface)
			{
				return;
			}

			$translation->setUrl(null);

			$uow->persist($translation);
		}

		$this->unsetTranslationChildrenUrl($entity, $uow);
	}

	/**
	 * Unset translation children url
	 *
	 * @param TreeInterface|TranslateInterface $entity
	 * @param UnitOfWork $uow
	 */
	private function unsetTranslationChildrenUrl(TreeInterface $entity, UnitOfWork $uow): void
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

				$uow->persist($translation);
			}

			$this->unsetTranslationChildrenUrl($child, $uow);
		}
	}
}