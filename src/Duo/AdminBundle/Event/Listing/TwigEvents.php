<?php

namespace Duo\AdminBundle\Event\Listing;

class TwigEvents
{
	/**
	 * @Event("Duo\AdminBundle\Event\Listing\TwigEvent")
	 */
	const CONTEXT = 'duo_admin.event.listing.twig.context';

	/**
	 * TwigEvents constructor
	 */
	private function __construct() {}
}
