<?php

namespace Duo\AdminBundle\Twig;

use Duo\AdminBundle\Tools\Menu\MenuBuilderInterface;
use Duo\AdminBundle\Tools\Menu\MenuInterface;

class MenuTwigExtension extends \Twig_Extension
{
	/**
	 * @var MenuBuilderInterface
	 */
	private $builder;

	/**
	 * MenuTwigExtension constructor
	 *
	 * @param MenuBuilderInterface $builder
	 */
	public function __construct(MenuBuilderInterface $builder)
	{
		$this->builder = $builder;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('get_admin_menu', [$this, 'getAdminMenu'])
		];
	}

	/**
	 * Get admin menu
	 *
	 * @param bool $rebuild [optional]
	 *
	 * @return MenuInterface
	 */
	public function getAdminMenu(bool $rebuild = false): MenuInterface
	{
		return $this->builder->createView($rebuild);
	}
}
