<?php

namespace Duo\BehaviorBundle\Event;

class DeleteEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\DeleteEvent")
	 */
	const DELETE = 'duo.event.behavior.delete';

	/**
	 * @Event("Duo\BehaviorBundle\Event\DeleteEvent")
	 */
	const UNDELETE = 'duo.event.behavior.undelete';

	/**
	 * DeleteEvents constructor
	 */
	private function __construct() {}
}