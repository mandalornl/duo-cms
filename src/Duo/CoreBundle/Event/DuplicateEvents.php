<?php

namespace Duo\CoreBundle\Event;

class DuplicateEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\DuplicateEvent")
	 */
	const DUPLICATE = 'duo.core.event.duplicate';

	/**
	 * DuplicateEvents constructor
	 */
	private function __construct() {}
}