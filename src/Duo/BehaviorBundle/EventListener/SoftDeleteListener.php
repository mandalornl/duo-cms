<?php

namespace Duo\BehaviorBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\BehaviorBundle\Entity\SoftDeleteInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Event\SoftDeleteEvent;

class SoftDeleteListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * SoftDeleteListener constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * On delete event
	 *
	 * @param SoftDeleteEvent $event
	 */
	public function onDelete(SoftDeleteEvent $event)
	{
		$entity = $event->getEntity();

		if ($entity instanceof TreeInterface)
		{
			$this->deleteChildren($entity);
		}
	}

	/**
	 * On undelete event
	 *
	 * @param SoftDeleteEvent $event
	 */
	public function onUndelete(SoftDeleteEvent $event)
	{
		$entity = $event->getEntity();

		if ($entity instanceof TreeInterface)
		{
			$this->undeleteChildren($entity);
		}
	}

	/**
	 * Delete children
	 *
	 * @param TreeInterface $entity
	 */
	private function deleteChildren(TreeInterface $entity)
	{
		foreach ($entity->getChildren() as $child)
		{
			/**
			 * @var SoftDeleteInterface|TreeInterface $child
			 */
			$child->delete();

			$this->deleteChildren($child);
		}
	}

	/**
	 * Undelete children
	 *
	 * @param TreeInterface $entity
	 */
	private function undeleteChildren(TreeInterface $entity)
	{
		foreach ($entity->getChildren() as $child)
		{
			/**
			 * @var SoftDeleteInterface|TreeInterface $child
			 */
			$child->undelete();

			$this->undeleteChildren($child);
		}
	}
}