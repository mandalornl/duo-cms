<?php

namespace Duo\BehaviorBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class PublishEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\PublishEvent")
	 */
	const PUBLISH = 'duo.behavior.event.publish';

	/**
	 * @Event("Duo\BehaviorBundle\Event\PublishEvent")
	 */
	const UNPUBLISH = 'duo.behavior.event.unpublish';

	/**
	 * PublishEvents constructor
	 */
	private function __construct() {}
}