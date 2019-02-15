<?php

namespace Duo\AdminBundle\Event\Listing;

class FormEvents
{
	/**
	 * Called before request is handled by form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\FormEvent")
	 */
	const PRE_CREATE = 'duo_admin.event.listing.form.preCreate';

	/**
	 * Called after request is handled by form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\FormEvent")
	 */
	const POST_CREATE = 'duo_admin.event.listing.form.postCreate';

	/**
	 * Called before request is handled by form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\FormEvent")
	 */
	const PRE_UPDATE = 'duo_admin.event.listing.form.preUpdate';

	/**
	 * Called after request is handled by form
	 *
	 * @Event("Duo\AdminBundle\Event\Listing\FormEvent")
	 */
	const POST_UPDATE = 'duo_admin.event.listing.form.postUpdate';

	/**
	 * FormEvents constructor
	 */
	private function __construct() {}
}
