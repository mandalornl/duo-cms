<?php

namespace Duo\AdminBundle\Event;

use Duo\AdminBundle\Menu\MenuBuilder;
use Symfony\Component\EventDispatcher\Event;

class MenuEvent extends Event
{
	/**
	 * @var MenuBuilder
	 */
	private $builder;

	/**
	 * MenuEvent constructor
	 *
	 * @param MenuBuilder $builder
	 */
	public function __construct(MenuBuilder $builder)
	{
		$this->builder = $builder;
	}

	/**
	 * Set builder
	 *
	 * @param MenuBuilder $builder
	 *
	 * @return MenuEvent
	 */
	public function setBuilder(MenuBuilder $builder): MenuEvent
	{
		$this->builder = $builder;

		return $this;
	}

	/**
	 * Get builder
	 *
	 * @return MenuBuilder
	 */
	public function getBuilder(): MenuBuilder
	{
		return $this->builder;
	}
}