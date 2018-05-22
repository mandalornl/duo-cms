<?php

namespace Duo\CoreBundle\Event;

class DeleteEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\DeleteEvent")
	 */
	const DELETE = 'duo.core.event.delete';

	/**
	 * @Event("Duo\CoreBundle\Event\DeleteEvent")
	 */
	const UNDELETE = 'duo.core.event.undelete';

	/**
	 * DeleteEvents constructor
	 */
	private function __construct() {}
}