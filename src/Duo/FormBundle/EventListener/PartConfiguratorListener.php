<?php

namespace Duo\FormBundle\EventListener;

use Duo\PartBundle\Event\PartConfiguratorEvent;
use Symfony\Component\Yaml\Yaml;

class PartConfiguratorListener
{
	/**
	 * On pre load event
	 *
	 * @param PartConfiguratorEvent $event
	 */
	public function preLoad(PartConfiguratorEvent $event)
	{
		$config = Yaml::parseFile(__DIR__ . '/../Resources/config/parts.yml');

		$configurator = $event->getConfigurator();
		$configurator->addConfig($config);
	}
}