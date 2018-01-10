<?php

namespace Duo\BehaviorBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class PublishEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\PublishEvent")
	 */
	const PUBLISH = 'duo.event.behavior.publish';

	/**
	 * @Event("Duo\BehaviorBundle\Event\PublishEvent")
	 */
	const UNPUBLISH = 'duo.event.behavior.unpublish';

	/**
	 * PublishEvents constructor
	 */
	private function __construct() {}
}