<?php

namespace Softmedia\AdminBundle\EventSubscriber\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Softmedia\AdminBundle\Entity\Behavior\TreeableTrait;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;

final class TreeableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
	{
		return [
			Events::loadClassMetadata
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

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null ||
			!ReflectionClassHelper::hasTrait($reflectionClass, TreeableTrait::class))
		{
			return;
		}

		$this->mapParent($classMetadata, $reflectionClass);
		$this->mapChildren($classMetadata, $reflectionClass);
	}

	/**
	 * Map parent
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapParent(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
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
	private function mapChildren(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
	{
		if (!$classMetadata->hasAssociation('children'))
		{
			$classMetadata->mapOneToMany([
				'fieldName'		=> 'children',
				'mappedBy'		=> 'parent',
				'cascade'		=> ['persist', 'merge', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'orphanRemoval'	=> true
			]);
		}
	}
}