<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\VersionableInterface;
use Duo\AdminBundle\Event\Behavior\VersionableEvent;

class VersionableListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * VersionableSubscriber constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * On post clone event
	 *
	 * @param VersionableEvent $event
	 */
	public function postClone(VersionableEvent $event)
	{
		/**
		 * @var VersionableInterface $entity
		 */
		foreach ($event->getOriginal()->getVersions() as $entity)
		{
			$entity->setVersion($event->getClone());

			$this->entityManager->persist($entity);
		}

		$this->entityManager->flush();
	}
}