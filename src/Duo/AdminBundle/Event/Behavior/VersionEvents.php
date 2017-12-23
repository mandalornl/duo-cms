<?php

namespace Duo\AdminBundle\Event\Behavior;

class VersionEvents
{
	/**
	 * CloneEvents constructor
	 */
	private function __construct() {}

	/**
	 * The preClone event is dispatched before ORM::prePersist
	 *
	 * @Event("Duo\AdminBundle\Event\VersionEvent")
	 */
	const PRE_CLONE = 'duo.version.preClone';

	/**
	 * The postClone event is dispatched after ORM::onFlush
	 *
	 * @Event("Duo\AdminBundle\Event\VersionEvent")
	 */
	const POST_CLONE = 'duo.version.postClone';

	/**
	 * The preRevert is dispatched before ORM::prePersist
	 *
	 * @Event("Duo\AdminBundle\Event|VersionEvent")
	 */
	const PRE_REVERT = 'duo.version.preRevert';

	/**
	 * The postRevert is dispatched after ORM::postFlush
	 *
	 * @Event("Duo\AdminBundle\Event|VersionEvent")
	 */
	const POST_REVERT = 'duo.version.postRevert';
}