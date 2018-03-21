<?php

namespace Duo\AdminBundle\Event;

class ListingORMEvents
{
	/**
	 * Called right after flush
	 *
	 * @Event("Duo\AdminBundle\Event\ListingORMEvent")
	 */
	const POST_FLUSH = 'duo.event.listing.orm.postFlush';

	/**
	 * ListingEvents constructor
	 */
	private function __construct() {}
}