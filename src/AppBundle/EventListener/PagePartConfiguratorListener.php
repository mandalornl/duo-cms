<?php

namespace AppBundle\EventListener;

use Duo\PageBundle\Event\PagePartConfiguratorEvent;
use Duo\PageBundle\Event\PagePartConfiguratorEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

class PagePartConfiguratorListener implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			PagePartConfiguratorEvents::PRE_LOAD => 'preLoad'
		];
	}

	/**
	 * On pre load event
	 *
	 * @param PagePartConfiguratorEvent $event
	 */
	public function preLoad(PagePartConfiguratorEvent $event): void
	{
		$config = Yaml::parseFile(__DIR__ . '/../Resources/config/pageparts.yml');

		$event->getConfigurator()->addConfig($config);
	}
}