<?php

namespace Duo\PartBundle\Event;

class PartConfiguratorEvents
{
	/**
	 * @Event("Duo|PartEvent\Event\PartConfiguratorEvent")
	 */
	const PRE_LOAD = 'duo.event.part_configurator.preLoad';

	/**
	 * PartConfiguratorEvents constructor
	 */
	private function __construct() {}
}