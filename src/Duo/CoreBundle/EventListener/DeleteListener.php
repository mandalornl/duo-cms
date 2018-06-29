<?php

namespace Duo\CoreBundle\EventListener;

use Duo\CoreBundle\Entity\DeleteInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Event\DeleteEvent;

class DeleteListener
{
	/**
	 * On delete event
	 *
	 * @param DeleteEvent $event
	 */
	public function onDelete(DeleteEvent $event): void
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
	public function onUndelete(DeleteEvent $event): void
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
	private function deleteChildren(TreeInterface $entity): void
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
	private function undeleteChildren(TreeInterface $entity): void
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