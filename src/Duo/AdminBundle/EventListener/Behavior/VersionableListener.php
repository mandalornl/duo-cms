<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\BlameableInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeletableInterface;
use Duo\AdminBundle\Entity\Behavior\SortableInterface;
use Duo\AdminBundle\Entity\Behavior\TreeableInterface;
use Duo\AdminBundle\Entity\Behavior\VersionableInterface;
use Duo\AdminBundle\Event\Behavior\VersionableEvent;

class VersionableListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * VersionableListener constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * On pre clone event
	 *
	 * @param VersionableEvent $event
	 */
	public function preClone(VersionableEvent $event)
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		/**
		 * @var VersionableInterface $version
		 */
		foreach ($origin->getVersions() as $version)
		{
			$version->setVersion($entity);
		}

		// reset blameable
		if ($entity instanceof BlameableInterface)
		{
			$entity
				->setCreatedBy(null)
				->setModifiedBy(null)
				->setDeletedBy(null);
		}

		// undelete entity
		if ($entity instanceof SoftDeletableInterface)
		{
			$entity->undelete();
		}
	}

	/**
	 * On pre revert event
	 *
	 * @param VersionableEvent $event
	 */
	public function preRevert(VersionableEvent $event)
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		/**
		 * @var VersionableInterface $version
		 */
		foreach ($origin->getVersions() as $version)
		{
			$version->setVersion($entity);
		}

		// update parent
		if ($entity instanceof TreeableInterface)
		{
			/**
			 * @var TreeableInterface $origin
			 */
			$entity->setParent($origin->getParent());
		}

		// update weight
		if ($entity instanceof SortableInterface)
		{
			/**
			 * @var SortableInterface $origin
			 */
			$entity->setWeight($origin->getWeight());
		}
	}
}