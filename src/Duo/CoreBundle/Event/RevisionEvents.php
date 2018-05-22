<?php

namespace Duo\CoreBundle\Event;

class RevisionEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\RevisionEvent")
	 */
	const CLONE = 'duo.core.event.revision.clone';

	/**
	 * @Event("Duo\CoreBundle\Event\RevisionEvent")
	 */
	const REVERT = 'duo.core.event.revision.revert';

	/**
	 * RevisionEvents constructor
	 */
	private function __construct() {}
}