<?php

namespace Duo\TaxonomyBundle\EventListener;

use Duo\AdminBundle\Event\MenuEvent;
use Symfony\Component\Yaml\Yaml;

class MenuListener
{
	/**
	 * On pre load event
	 *
	 * @param MenuEvent $event
	 */
	public function preLoad(MenuEvent $event)
	{
		$config = Yaml::parseFile(__DIR__ . '/../Resources/config/menu.yml');

		$builder = $event->getBuilder();
		$builder->addConfig($config);
	}
}