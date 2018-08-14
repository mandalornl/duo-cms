<?php

namespace Duo\AdminBundle\Event;

use Duo\AdminBundle\Tools\Menu\MenuBuilderInterface;
use Symfony\Component\EventDispatcher\Event;

class MenuEvent extends Event
{
	/**
	 * @var MenuBuilderInterface
	 */
	private $builder;

	/**
	 * MenuEvent constructor
	 *
	 * @param MenuBuilderInterface $builder
	 */
	public function __construct(MenuBuilderInterface $builder)
	{
		$this->builder = $builder;
	}

	/**
	 * Set builder
	 *
	 * @param MenuBuilderInterface $builder
	 *
	 * @return MenuEvent
	 */
	public function setBuilder(MenuBuilderInterface $builder): MenuEvent
	{
		$this->builder = $builder;

		return $this;
	}

	/**
	 * Get builder
	 *
	 * @return MenuBuilderInterface
	 */
	public function getBuilder(): MenuBuilderInterface
	{
		return $this->builder;
	}
}