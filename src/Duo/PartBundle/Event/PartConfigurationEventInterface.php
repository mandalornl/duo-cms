<?php

namespace Duo\PartBundle\Event;

use Duo\PartBundle\Configurator\PartConfiguratorInterface;

interface PartConfigurationEventInterface
{
	/**
	 * Set configurator
	 *
	 * @param PartConfiguratorInterface $configurator
	 *
	 * @return PartConfigurationEventInterface
	 */
	public function setConfigurator(PartConfiguratorInterface $configurator): PartConfigurationEventInterface;

	/**
	 * Get configurator
	 *
	 * @return PartConfiguratorInterface
	 */
	public function getConfigurator(): PartConfiguratorInterface;
}