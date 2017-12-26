<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\SortInterface;
use Duo\AdminBundle\Entity\Behavior\VersionInterface;
use Duo\AdminBundle\Event\Behavior\SortEvent;

class SortListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * SortableEvent constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * On sort event
	 *
	 * @param SortEvent $event
	 */
	public function onSort(SortEvent $event)
	{
//		$entity = $event->getEntity();
//
//		// update weight
//		if ($entity instanceof VersionInterface)
//		{
//			/**
//			 * @var SortInterface $version
//			 */
//			foreach ($entity->getVersions() as $version)
//			{
//				$version->setWeight($entity->getWeight());
//			}
//		}
	}
}