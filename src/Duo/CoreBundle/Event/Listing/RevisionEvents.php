<?php

namespace Duo\CoreBundle\Event\Listing;

final class RevisionEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\Listing\RevisionEvent")
	 */
	const CLONE = 'duo.core.event.listing.revision.clone';

	/**
	 * @Event("Duo\CoreBundle\Event\Listing\RevisionEvent")
	 */
	const REVERT = 'duo.core.event.listing.revision.revert';

	/**
	 * RevisionEvents constructor
	 */
	private function __construct() {}
}