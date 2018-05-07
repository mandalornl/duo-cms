<?php

namespace Duo\AdminBundle\Event\Listing;

class ORMEvents
{
	/**
	 * Called right after flush
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\ORMEvent")
	 */
	const POST_FLUSH = 'duo.event.listing.orm.postFlush';

	/**
	 * ORMEvents constructor
	 */
	private function __construct() {}
}