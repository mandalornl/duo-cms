<?php

namespace Duo\AdminBundle\Event\Listing;

class FormEvents
{
	/**
	 * Called before request is handled by form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\FormEvent")
	 */
	const PRE_ADD = 'duo.event.listing.form.preAdd';

	/**
	 * Called before request is handled by form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\FormEvent")
	 */
	const PRE_EDIT = 'duo.event.listing.form.preEdit';

	/**
	 * FormEvents constructor
	 */
	private function __construct() {}
}