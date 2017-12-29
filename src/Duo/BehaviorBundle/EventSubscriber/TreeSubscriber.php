<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Duo\AdminBundle\Helper\ReflectionClassHelper;
use Duo\BehaviorBundle\Entity\TreeTrait;

class TreeSubscriber implements EventSubscriber
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

		if ($classMetadata->getReflectionClass() === null)
		{
			return;
		}

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null ||
			!ReflectionClassHelper::hasTrait($reflectionClass, TreeTrait::class))
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
			$mapping = [
				'fieldName'		=> 'children',
				'mappedBy'		=> 'parent',
				'cascade'		=> ['persist', 'merge', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'orphanRemoval'	=> true
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
}