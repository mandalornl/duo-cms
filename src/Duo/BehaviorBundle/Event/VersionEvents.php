<?php

namespace Duo\BehaviorBundle\Event;

class VersionEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\VersionEvent")
	 */
	const CLONE = 'duo.event.behavior.clone';

	/**
	 * @Event("Duo\BehaviorBundle\Event|VersionEvent")
	 */
	const REVERT = 'duo.event.behavior.revert';

	/**
	 * VersionEvents constructor
	 */
	private function __construct() {}
}