<?php

namespace Duo\AdminBundle\Event\Behavior;

class VersionEvents
{
	/**
	 * @Event("Duo\AdminBundle\Event\VersionEvent")
	 */
	const CLONE = 'duo.event.onClone';

	/**
	 * @Event("Duo\AdminBundle\Event|VersionEvent")
	 */
	const REVERT = 'duo.event.onRevert';

	/**
	 * VersionEvents constructor
	 */
	private function __construct() {}
}