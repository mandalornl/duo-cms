<?php

namespace Duo\CoreBundle\Event\Listing;

final class DuplicateEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\Listing\DuplicateEvent")
	 */
	const DUPLICATE = 'duo_core.event.listing.duplicate';

	/**
	 * DuplicateEvents constructor
	 */
	private function __construct() {}
}
