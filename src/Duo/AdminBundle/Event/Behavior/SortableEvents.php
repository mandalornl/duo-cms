<?php

namespace Duo\AdminBundle\Event\Behavior;

class SortableEvents
{
	/**
	 * SortableEvents constructor
	 */
	private function __construct() {}

	/**
	 * The preSort event is dispatched before ORM::prePersist
	 *
	 * @Event("Duo\AdminBundle\Event\Behavior\SortableEvent")
	 */
	const PRE_SORT = 'due.sortable.preSort';

	/**
	 * The postSort event is dispatched after ORM::postFlush
	 *
	 * @Event("Duo\AdminBundle\Event\Behavior\SortableEvent")
	 */
	const POST_SORT = 'due.sortable.postSort';
}