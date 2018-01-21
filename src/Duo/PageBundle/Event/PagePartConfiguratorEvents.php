<?php

namespace Duo\PageBundle\Event;

class PagePartConfiguratorEvents
{
	/**
	 * @Event("Duo\PageBundle\Event\PagePartConfiguratorEvent")
	 */
	const PRE_LOAD = 'duo.event.page_part_configurator.preLoad';

	/**
	 * PagePartConfiguratorEvents constructor
	 */
	private function __construct() {}
}