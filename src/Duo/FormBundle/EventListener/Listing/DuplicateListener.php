<?php

namespace Duo\FormBundle\EventListener\Listing;

use Duo\CoreBundle\Event\Listing\DuplicateEvent;
use Duo\CoreBundle\Event\Listing\DuplicateEvents;
use Duo\FormBundle\Entity\Form;
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

		if (!$entity instanceof Form || $entity->getName() === null)
		{
			return;
		}

		// ensure unique name
		$entity->setName(uniqid("{$entity->getName()}-"));
	}
}