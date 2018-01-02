<?php

namespace Duo\AdminBundle\Event;

class TwigEvents
{
	/**
	 * @Event("Duo\AdminBundle\Event\TwigEvent")
	 */
	const CONTEXT = 'duo.admin.event.onTwigContext';

	/**
	 * TwigEvents constructor
	 */
	private function __construct() {}
}