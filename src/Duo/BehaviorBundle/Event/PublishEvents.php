<?php

namespace Duo\BehaviorBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class PublishEvents
{
	/**
	 * @Event("Duo\BehaviorBundle\Event\PublishEvent")
	 */
	const PUBLISH = 'duo.behavior.event.onPublish';

	/**
	 * @Event("Duo\BehaviorBundle\Event\PublishEvent")
	 */
	const UNPUBLISH = 'duo.behavior.event.onUnpublish';

	/**
	 * PublishEvents constructor
	 */
	private function __construct() {}
}