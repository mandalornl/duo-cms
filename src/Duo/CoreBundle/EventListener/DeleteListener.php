<?php

namespace Duo\CoreBundle\EventListener;

use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Event\DeleteEvent;
use Duo\CoreBundle\Event\DeleteEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DeleteListener implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			DeleteEvents::DELETE => 'onDelete',
			DeleteEvents::UNDELETE => 'onUndelete'
		];
	}

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

		if ($entity instanceof TreeInterface)
		{
			$this->undeleteChildren($entity);
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