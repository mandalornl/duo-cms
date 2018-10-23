<?php

namespace Duo\CoreBundle\Event\Listing;

final class SortEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\Listing\SortEvent")
	 */
	const SORT = 'due.core.event.listing.sort';

	/**
	 * SortEvents constructor
	 */
	private function __construct() {}
}