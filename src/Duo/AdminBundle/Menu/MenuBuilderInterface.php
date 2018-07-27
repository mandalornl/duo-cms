<?php

namespace Duo\AdminBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface MenuBuilderInterface
{
	/**
	 * Add config
	 *
	 * @param array $config
	 *
	 * @return MenuBuilderInterface
	 */
	public function addConfig(array $config): MenuBuilderInterface;

	/**
	 * Remove config
	 *
	 * @param array $config
	 *
	 * @return MenuBuilderInterface
	 */
	public function removeConfig(array $config): MenuBuilderInterface;

	/**
	 * Get configs
	 *
	 * @return ArrayCollection
	 */
	public function getConfigs(): Collection;

	/**
	 * Set menu
	 *
	 * @param MenuInterface $menu
	 *
	 * @return MenuBuilderInterface
	 */
	public function setMenu(MenuInterface $menu): MenuBuilderInterface;

	/**
	 * Get menu
	 *
	 * @return MenuInterface
	 */
	public function getMenu(): MenuInterface;

	/**
	 * Create view
	 *
	 * @return MenuInterface
	 */
	public function createView(): MenuInterface;
}