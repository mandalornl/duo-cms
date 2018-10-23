<?php

namespace Duo\AdminBundle\Event\Listing;

class EntityEvents
{
	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\EntityEvent")
	 */
	const PRE_CREATE = 'duo.admin.event.listing.entity.preCreate';

	/**
	 * Called before entity is added to form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\EntityEvent")
	 */
	const PRE_UPDATE = 'duo.admin.event.listing.entity.preUpdate';

	/**
	 * EntityEvents constructor
	 */
	private function __construct() {}
}