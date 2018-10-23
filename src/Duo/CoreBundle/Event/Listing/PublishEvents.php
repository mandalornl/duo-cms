<?php

namespace Duo\CoreBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;

final class PublishEvents
{
	/**
	 * @Event("Duo\CoreBundle\Event\Listing\PublishEvent")
	 */
	const PUBLISH = 'duo.core.event.listing.publish';

	/**
	 * @Event("Duo\CoreBundle\Event\Listing\PublishEvent")
	 */
	const UNPUBLISH = 'duo.core.event.listing.unpublish';

	/**
	 * PublishEvents constructor
	 */
	private function __construct() {}
}