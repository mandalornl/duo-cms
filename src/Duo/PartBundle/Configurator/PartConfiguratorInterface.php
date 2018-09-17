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
	 * @param bool $isView [optional]
	 * @param string[] $ids [optional]
	 *
	 * @return array
	 */
	public function getTypes(bool $isView = false, array $ids = []): array;

	/**
	 * Get sections
	 *
	 * @param bool $isView [optional]
	 *
	 * @return array
	 */
	public function getSections(bool $isView = false): array;

	/**
	 * Get icons
	 *
	 * @param bool $isView [optional]
	 *
	 * @return array
	 */
	public function getIcons(bool $isView = false): array;
}