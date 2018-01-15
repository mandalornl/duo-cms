<?php

namespace Duo\BehaviorBundle\Event;

class RevisionEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\RevisionEvent")
	 */
	const CLONE = 'duo.event.revision.clone';

	/**
	 * @Event("Duo\BehaviorBundle\Event\RevisionEvent")
	 */
	const REVERT = 'duo.event.revision.revert';

	/**
	 * RevisionEvents constructor
	 */
	private function __construct() {}
}