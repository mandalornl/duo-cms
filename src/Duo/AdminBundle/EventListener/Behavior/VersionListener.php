<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\BlameInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeleteInterface;
use Duo\AdminBundle\Entity\Behavior\SortInterface;
use Duo\AdminBundle\Entity\Behavior\TreeInterface;
use Duo\AdminBundle\Entity\Behavior\VersionInterface;
use Duo\AdminBundle\Event\Behavior\VersionEvent;

class VersionListener
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
	 * @param VersionEvent $event
	 */
	public function preClone(VersionEvent $event)
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		/**
		 * @var VersionInterface $version
		 */
		foreach ($origin->getVersions() as $version)
		{
			$version->setVersion($entity);
		}

		// reset blameable
		if ($entity instanceof BlameInterface)
		{
			$entity
				->setCreatedBy(null)
				->setModifiedBy(null)
				->setDeletedBy(null);
		}

		// undelete entity
		if ($entity instanceof SoftDeleteInterface)
		{
			$entity->undelete();
		}
	}

	/**
	 * On pre revert event
	 *
	 * @param VersionEvent $event
	 */
	public function preRevert(VersionEvent $event)
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		/**
		 * @var VersionInterface $version
		 */
		foreach ($origin->getVersions() as $version)
		{
			$version->setVersion($entity);
		}

		// update parent
		if ($entity instanceof TreeInterface)
		{
			/**
			 * @var TreeInterface $origin
			 */
			$entity->setParent($origin->getParent());
		}

		// update weight
		if ($entity instanceof SortInterface)
		{
			/**
			 * @var SortInterface $origin
			 */
			$entity->setWeight($origin->getWeight());
		}
	}
}