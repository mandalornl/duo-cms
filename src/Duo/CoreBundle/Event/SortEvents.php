<?php

namespace Duo\CoreBundle\Event;

class SortEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\SortEvent")
	 */
	const SORT = 'due.core.event.sort';

	/**
	 * SortEvents constructor
	 */
	private function __construct() {}
}