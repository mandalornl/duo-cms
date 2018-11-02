<?php

namespace Duo\PageBundle\EventListener\Listing;

use Duo\CoreBundle\Event\Listing\DuplicateEvent;
use Duo\CoreBundle\Event\Listing\DuplicateEvents;
use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Entity\PageTranslationInterface;
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

		if (!$entity instanceof PageInterface)
		{
			return;
		}

		// ensure unique name
		if ($entity->getName() !== null)
		{
			$entity->setName(uniqid("{$entity->getName()}-"));
		}

		// ensure unique slug, which in turn results in a unique url
		foreach ($entity->getTranslations() as $translation)
		{
			/**
			 * @var PageTranslationInterface $translation
			 */
			$translation->setSlug(uniqid("{$translation->getSlug()}-"));
		}
	}
}