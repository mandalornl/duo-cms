<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\SortableInterface;
use Duo\AdminBundle\Entity\Behavior\VersionableInterface;
use Duo\AdminBundle\Event\Behavior\SortableEvent;

class SortableListener
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
	 * On pre sort event
	 *
	 * @param SortableEvent $event
	 */
	public function preSort(SortableEvent $event)
	{
		$entity = $event->getEntity();

		// update weight
		if ($entity instanceof VersionableInterface)
		{
			/**
			 * @var SortableInterface $version
			 */
			foreach ($entity->getVersions() as $version)
			{
				$version->setWeight($entity->getWeight());
			}
		}
	}
}