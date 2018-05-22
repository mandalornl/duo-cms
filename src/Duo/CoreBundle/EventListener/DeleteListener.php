<?php

namespace Duo\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\CoreBundle\Entity\DeleteInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Event\DeleteEvent;

class DeleteListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * DeleteListener constructor
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
	 * @param DeleteEvent $event
	 */
	public function onDelete(DeleteEvent $event)
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
	 * @param DeleteEvent $event
	 */
	public function onUndelete(DeleteEvent $event)
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
			 * @var DeleteInterface|TreeInterface $child
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
			 * @var DeleteInterface|TreeInterface $child
			 */
			$child->undelete();

			$this->undeleteChildren($child);
		}
	}
}