<?php

namespace Duo\AdminBundle\Event;

class ListingEvents
{
	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\ListingEvent")
	 */
	const PRE_ADD = 'duo.event.listing.preAdd';

	/**
	 * Called right after form has been submitted
	 *
	 * @Event("Duo\AdminBundle\Event\ListingEvent")
	 */
	const POST_ADD = 'duo.event.listing.postAdd';

	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\ListingEvent")
	 */
	const PRE_EDIT = 'duo.event.listing.preEdit';

	/**
	 * Called right after form has been submitted
	 *
	 * @Event("Duo\AdminBundle\Event\ListingEvent")
	 */
	const POST_EDIT = 'duo.event.listing.postEdit';

	/**
	 * ListingEvents constructor
	 */
	private function __construct() {}
}