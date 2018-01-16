<?php

namespace Duo\PagePartBundle\Event;

class PagePartConfiguratorEvents
{
	/**
	 * @Event("Duo|PagePartEvent\Event\PagePartConfiguratorEvent")
	 */
	const PRE_LOAD = 'duo.event.page_part_configurator.preLoad';

	/**
	 * PagePartConfiguratorEvents constructor
	 */
	private function __construct() {}
}