<?php

namespace Duo\FormBundle\Event;

class FormPartConfiguratorEvents
{
	/**
	 * @Event("Duo\FormBundle\Event\FormPartConfiguratorEvent")
	 */
	const PRE_LOAD = 'duo.event.form_part_configurator.preLoad';

	/**
	 * FormPartConfiguratorEvents constructor
	 */
	private function __construct() {}
}