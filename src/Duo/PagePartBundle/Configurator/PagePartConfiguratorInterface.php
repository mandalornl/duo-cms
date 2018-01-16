<?php

namespace Duo\PagePartBundle\Configurator;

use Doctrine\Common\Collections\ArrayCollection;

interface PagePartConfiguratorInterface
{
	/**
	 * Add config
	 *
	 * @param array $config
	 *
	 * @return PagePartConfiguratorInterface
	 */
	public function addConfig(array $config): PagePartConfiguratorInterface;

	/**
	 * Remove config
	 *
	 * @param array $config
	 *
	 * @return PagePartConfiguratorInterface
	 */
	public function removeConfig(array $config): PagePartConfiguratorInterface;

	/**
	 * Get configs
	 *
	 * @return ArrayCollection
	 */
	public function getConfigs();

	/**
	 * Get types
	 *
	 * @return array
	 */
	public function getTypes(): array;

	/**
	 * Get icons
	 *
	 * @return array
	 */
	public function getIcons(): array;
}