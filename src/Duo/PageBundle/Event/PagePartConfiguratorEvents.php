<?php

namespace Duo\PageBundle\Event;

final class PagePartConfiguratorEvents
{
	/**
	 * @Event("Duo\PageBundle\Event\PagePartConfiguratorEvent")
	 */
	const PRE_LOAD = 'duo_page.event.page_part_configurator.preLoad';

	/**
	 * PagePartConfiguratorEvents constructor
	 */
	private function __construct() {}
}
