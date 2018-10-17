<?php

namespace Duo\PageBundle\EventListener;

use Duo\AdminBundle\Helper\IncrementerHelper;
use Duo\CoreBundle\Event\DuplicateEvent;
use Duo\CoreBundle\Event\DuplicateEvents;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DuplicateListener implements EventSubscriberInterface
{
	/**
	 * @var IncrementerHelper
	 */
	private $incrementerHelper;

	/**
	 * DuplicateListener constructor
	 *
	 * @param IncrementerHelper $incrementerHelper
	 */
	public function __construct(IncrementerHelper $incrementerHelper)
	{
		$this->incrementerHelper = $incrementerHelper;
	}

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

		$entity->setName(
			$this->incrementerHelper->getValue($entity, 'name')
		);
	}
}