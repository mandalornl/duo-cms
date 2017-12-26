<?php

namespace Duo\AdminBundle\Event\Behavior;

class SortEvents
{
	/**
	 * @Event("Duo\AdminBundle\Event\Behavior\SortEvent")
	 */
	const SORT = 'due.event.onSort';

	/**
	 * SortEvents constructor
	 */
	private function __construct() {}
}