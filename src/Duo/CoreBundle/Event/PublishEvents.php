<?php

namespace Duo\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class PublishEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\PublishEvent")
	 */
	const PUBLISH = 'duo.core.event.publish';

	/**
	 * @Event("Duo\CoreBundle\Event\PublishEvent")
	 */
	const UNPUBLISH = 'duo.core.event.unpublish';

	/**
	 * PublishEvents constructor
	 */
	private function __construct() {}
}