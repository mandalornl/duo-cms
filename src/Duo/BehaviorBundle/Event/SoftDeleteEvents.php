<?php

namespace Duo\BehaviorBundle\Event;

class SoftDeleteEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\SoftDeleteEvent")
	 */
	const DELETE = 'duo.behavior.event.onDelete';

	/**
	 * @Event("Duo\BehaviorBundle\Event\SoftDeleteEvent")
	 */
	const UNDELETE = 'duo.behavior.event.onUndelete';

	/**
	 * SoftDeleteEvents constructor
	 */
	private function __construct() {}
}