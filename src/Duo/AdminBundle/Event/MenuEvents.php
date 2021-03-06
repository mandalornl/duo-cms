<?php

namespace Duo\AdminBundle\Event;

class MenuEvents
{
	/**
	 * @Event("Duo\AdminBundle\Event\MenuEvent")
	 */
	const PRE_LOAD = 'duo_admin.event.menu.preLoad';

	/**
	 * @Event("Duo\AdminBundle\Event\MenuEvent")
	 */
	const PRE_BUILD = 'duo_admin.event.menu.preBuild';

	/**
	 * @Event("Duo\AdminBundle\Event\MenuEvent")
	 */
	const POST_BUILD = 'duo_admin.event.menu.postBuild';

	/**
	 * MenuEvents constructor
	 */
	private function __construct() {}
}
