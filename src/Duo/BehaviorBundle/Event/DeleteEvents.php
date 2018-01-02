<?php

namespace Duo\BehaviorBundle\Event;

class DeleteEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\DeleteEvent")
	 */
	const DELETE = 'duo.behavior.event.onDelete';

	/**
	 * @Event("Duo\BehaviorBundle\Event\DeleteEvent")
	 */
	const UNDELETE = 'duo.behavior.event.onUndelete';

	/**
	 * DeleteEvents constructor
	 */
	private function __construct() {}
}