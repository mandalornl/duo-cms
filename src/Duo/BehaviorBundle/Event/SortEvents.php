<?php

namespace Duo\BehaviorBundle\Event;

class SortEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\SortEvent")
	 */
	const SORT = 'due.behavior.event.onSort';

	/**
	 * SortEvents constructor
	 */
	private function __construct() {}
}