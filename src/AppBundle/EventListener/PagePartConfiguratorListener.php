<?php

namespace AppBundle\EventListener;

use Duo\PageBundle\Event\PagePartConfiguratorEvent;
use Symfony\Component\Yaml\Yaml;

class PagePartConfiguratorListener
{
	/**
	 * On pre load event
	 *
	 * @param PagePartConfiguratorEvent $event
	 */
	public function preLoad(PagePartConfiguratorEvent $event): void
	{
		$config = Yaml::parseFile(__DIR__ . '/../Resources/config/pageparts.yml');

		$configurator = $event->getConfigurator();
		$configurator->addConfig($config);
	}
}