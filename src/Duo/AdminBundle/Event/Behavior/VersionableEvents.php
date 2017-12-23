<?php

namespace Duo\AdminBundle\Event\Behavior;

class VersionableEvents
{
	/**
	 * CloneEvents constructor
	 */
	private function __construct() {}

	/**
	 * The preClone event is dispatched before ORM::prePersist
	 *
	 * @Event("Duo\AdminBundle\Event\VersionableEvent")
	 */
	const PRE_CLONE = 'duo.versionable.preClone';

	/**
	 * The postClone event is dispatched after ORM::onFlush
	 *
	 * @Event("Duo\AdminBundle\Event\VersionableEvent")
	 */
	const POST_CLONE = 'duo.versionable.postClone';

	/**
	 * The preRevert is dispatched before ORM::prePersist
	 *
	 * @Event("Duo\AdminBundle\Event|VersionableEvent")
	 */
	const PRE_REVERT = 'duo.versionable.preRevert';

	/**
	 * The postRevert is dispatched after ORM::postFlush
	 *
	 * @Event("Duo\AdminBundle\Event|VersionableEvent")
	 */
	const POST_REVERT = 'duo.versionable.postRevert';
}