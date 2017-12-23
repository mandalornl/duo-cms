<?php

namespace Duo\AdminBundle\Event\Behavior;

class SortEvents
{
	/**
	 * SortEvents constructor
	 */
	private function __construct() {}

	/**
	 * The preSort event is dispatched before ORM::prePersist
	 *
	 * @Event("Duo\AdminBundle\Event\Behavior\SortEvent")
	 */
	const PRE_SORT = 'due.sort.preSort';

	/**
	 * The postSort event is dispatched after ORM::postFlush
	 *
	 * @Event("Duo\AdminBundle\Event\Behavior\SortEvent")
	 */
	const POST_SORT = 'due.sort.postSort';
}