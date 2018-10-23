<?php

namespace Duo\CoreBundle\Event\Listing;

final class DeleteEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\Listing\DeleteEvent")
	 */
	const DELETE = 'duo.core.event.listing.delete';

	/**
	 * @Event("Duo\CoreBundle\Event\Listing\DeleteEvent")
	 */
	const UNDELETE = 'duo.core.event.listing.undelete';

	/**
	 * DeleteEvents constructor
	 */
	private function __construct() {}
}