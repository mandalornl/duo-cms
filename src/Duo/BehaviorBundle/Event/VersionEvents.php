<?php

namespace Duo\BehaviorBundle\Event;

class VersionEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\VersionEvent")
	 */
	const CLONE = 'duo.behavior.event.onClone';

	/**
	 * @Event("Duo\BehaviorBundle\Event|VersionEvent")
	 */
	const REVERT = 'duo.behavior.event.onRevert';

	/**
	 * VersionEvents constructor
	 */
	private function __construct() {}
}