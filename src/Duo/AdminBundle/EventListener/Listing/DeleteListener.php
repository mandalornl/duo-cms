<?php

namespace Duo\AdminBundle\EventListener\Listing;

use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Event\Listing\DeleteEvent;

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

		if (!$entity instanceof TreeInterface)
		{
			return;
		}

		$this->deleteChildren($entity);
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
	 * On undelete event
	 *
	 * @param DeleteEvent $event
	 */
	public function onUndelete(DeleteEvent $event): void
	{
		$entity = $event->getEntity();

		if (!$entity instanceof TreeInterface)
		{
			return;
		}

		$this->undeleteChildren($entity);
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
