<?php

namespace Duo\FormBundle\Event;

final class FormPartConfiguratorEvents
{
	/**
	 * @Event("Duo\FormBundle\Event\FormPartConfiguratorEvent")
	 */
	const PRE_LOAD = 'duo_form.event.form_part_configurator.preLoad';

	/**
	 * FormPartConfiguratorEvents constructor
	 */
	private function __construct() {}
}
