<?php

namespace Duo\PageBundle\EventListener\Listing;

use Duo\CoreBundle\Event\Listing\DuplicateEvent;
use Duo\CoreBundle\Event\Listing\DuplicateEvents;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DuplicateListener implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			DuplicateEvents::DUPLICATE => 'onDuplicate'
		];
	}

	/**
	 * On duplicate event
	 *
	 * @param DuplicateEvent $event
	 */
	public function onDuplicate(DuplicateEvent $event): void
	{
		$entity = $event->getEntity();

		if (!$entity instanceof PageInterface || $entity->getName() === null)
		{
			return;
		}

		// ensure unique name
		$entity->setName(uniqid("{$entity->getName()}-"));
	}
}