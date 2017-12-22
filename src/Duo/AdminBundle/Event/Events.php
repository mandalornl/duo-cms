<?php

namespace Duo\AdminBundle\Event;

class Events
{
	/**
	 * Fires after versionable entity is cloned, but before it has been saved
	 *
	 * @var string
	 */
	const VERSIONABLE_PRE_CLONE = 'duo_admin.versionable.preClone';

	/**
	 * Fires after versionable clone has been saved
	 *
	 * @var string
	 */
	const VERSIONABLE_POST_CLONE = 'duo_admin.versionable.postClone';
}