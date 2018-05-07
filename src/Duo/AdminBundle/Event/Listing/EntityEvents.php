<?php

namespace Duo\AdminBundle\Event\Listing;

class EntityEvents
{
	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\EntityEvent")
	 */
	const PRE_ADD = 'duo.event.listing.entity.preAdd';

	/**
	 * Called right after form has been submitted
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\EntityEvent")
	 */
	const POST_ADD = 'duo.event.listing.entity.postAdd';

	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\EntityEvent")
	 */
	const PRE_EDIT = 'duo.event.listing.entity.preEdit';

	/**
	 * Called right after form has been submitted
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\EntityEvent")
	 */
	const POST_EDIT = 'duo.event.listing.entity.postEdit';

	/**
	 * EntityEvents constructor
	 */
	private function __construct() {}
}