<?php

namespace Duo\AdminBundle\Event;

class TwigEvents
{
	/**
	 * @Event("Duo\AdminBundle\Event\TwigEvent")
	 */
	const CONTEXT = 'duo.event.twig.context';

	/**
	 * TwigEvents constructor
	 */
	private function __construct() {}
}