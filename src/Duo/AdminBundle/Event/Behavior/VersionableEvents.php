<?php

namespace Duo\AdminBundle\Event\Behavior;

class VersionableEvents
{
	/**
	 * CloneEvents constructor
	 */
	private function __construct() {}

	/**
	 * @Event("Duo\AdminBundle\Event\VersionableEvent")
	 */
	const PRE_PERSIST = 'duo.versionable.prePersist';

	/**
	 * @Event("Duo\AdminBundle\Event\VersionableEvent")
	 */
	const POST_FLUSH = 'duo.versionable.postFlush';
}