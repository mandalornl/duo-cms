<?php

namespace Duo\PartBundle\Configurator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface PartConfiguratorInterface
{
	/**
	 * Dispatch pre load event
	 */
	public function dispatchPreLoadEvent(): void;

	/**
	 * Add config
	 *
	 * @param array $config
	 *
	 * @return PartConfiguratorInterface
	 */
	public function addConfig(array $config): PartConfiguratorInterface;

	/**
	 * Remove config
	 *
	 * @param array $config
	 *
	 * @return PartConfiguratorInterface
	 */
	public function removeConfig(array $config): PartConfiguratorInterface;

	/**
	 * Get configs
	 *
	 * @return ArrayCollection
	 */
	public function getConfigs(): Collection;

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