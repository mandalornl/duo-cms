<?php

namespace Duo\NodeBundle\EventListener;

use Duo\AdminBundle\Helper\IncrementerHelper;
use Duo\CoreBundle\Event\DuplicateEvent;
use Duo\CoreBundle\Event\DuplicateEvents;
use Duo\NodeBundle\Entity\NodeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DuplicateListener implements EventSubscriberInterface
{
	/**
	 * @var IncrementerHelper
	 */
	private $incrementHelper;

	/**
	 * DuplicateListener constructor
	 *
	 * @param IncrementerHelper $incrementHelper
	 */
	public function __construct(IncrementerHelper $incrementHelper)
	{
		$this->incrementHelper = $incrementHelper;
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

		if (!$entity instanceof NodeInterface)
		{
			return;
		}

		$name = $this->incrementHelper->getValue($entity, 'name');

		$entity->setName($name);
	}
}