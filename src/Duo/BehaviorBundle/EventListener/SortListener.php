<?php

namespace Duo\BehaviorBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\BehaviorBundle\Event\SortEvent;

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