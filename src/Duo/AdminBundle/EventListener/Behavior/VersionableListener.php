<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\BlameableInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeletableInterface;
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
	 * On pre persist event
	 *
	 * @param VersionableEvent $event
	 */
	public function prePersist(VersionableEvent $event)
	{
		$entity = $event->getClone();

		// reset blameable
		if ($entity instanceof BlameableInterface)
		{
			$entity
				->setCreatedBy(null)
				->setModifiedBy(null)
				->setDeletedBy(null);
		}

		// reset soft delete
		if ($entity instanceof SoftDeletableInterface)
		{
			$entity->undelete();
		}
	}

	/**
	 * On post flush event
	 *
	 * @param VersionableEvent $event
	 */
	public function postFlush(VersionableEvent $event)
	{
		// update versions
		foreach ($event->getOriginal()->getVersions() as $entity)
		{
			/**
			 * @var VersionableInterface $entity
			 */
			$entity->setVersion($event->getClone());

			$this->entityManager->persist($entity);
		}

		$this->entityManager->flush();
	}
}