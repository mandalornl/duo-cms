<?php

namespace Duo\DraftBundle\EventListener;

use Duo\CoreBundle\Event\Listing\RevisionEvent;
use Duo\CoreBundle\Event\Listing\RevisionEvents;
use Duo\DraftBundle\Entity\Property\DraftInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RevisionListener implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			RevisionEvents::CLONE => 'onClone',
			RevisionEvents::REVERT => 'onRevert'
		];
	}

	/**
	 * On clone event
	 *
	 * @param RevisionEvent $event
	 */
	public function onClone(RevisionEvent $event): void
	{
		$this->setDrafts($event);
	}

	/**
	 * On revert event
	 *
	 * @param RevisionEvent $event
	 */
	public function onRevert(RevisionEvent $event): void
	{
		$this->setDrafts($event);
	}

	/**
	 * Set drafts
	 *
	 * @param RevisionEvent $event
	 */
	private function setDrafts(RevisionEvent $event): void
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		if (!$entity instanceof DraftInterface || !$origin instanceof DraftInterface)
		{
			return;
		}

		foreach ($origin->getDrafts() as $draft)
		{
			$draft->setEntity($entity);
		}
	}
}