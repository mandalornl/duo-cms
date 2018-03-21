<?php

namespace Duo\AdminBundle\Event;

class ListingFormEvents
{
	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\ListingFormEvent")
	 */
	const PRE_ADD = 'duo.event.listing.form.preAdd';

	/**
	 * Called right after form has been submitted
	 *
	 * @Event("Duo\AdminBundle\Event\ListingFormEvent")
	 */
	const POST_ADD = 'duo.event.listing.form.postAdd';

	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\ListingFormEvent")
	 */
	const PRE_EDIT = 'duo.event.listing.form.preEdit';

	/**
	 * Called right after form has been submitted
	 *
	 * @Event("Duo\AdminBundle\Event\ListingFormEvent")
	 */
	const POST_EDIT = 'duo.event.listing.form.postEdit';

	/**
	 * ListingEvents constructor
	 */
	private function __construct() {}
}