<?php

namespace Duo\PartBundle\Event;

use Duo\PartBundle\Configurator\PartConfiguratorInterface;
use Symfony\Component\EventDispatcher\Event;

class PartConfiguratorEvent extends Event
{
	/**
	 * @var PartConfiguratorInterface
	 */
	private $configurator;

	/**
	 * PartConfiguratorEvent constructor
	 *
	 * @param PartConfiguratorInterface $configurator
	 */
	public function __construct(PartConfiguratorInterface $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * Set configurator
	 *
	 * @param PartConfiguratorInterface $configurator
	 *
	 * @return PartConfiguratorEvent
	 */
	public function setConfigurator(PartConfiguratorInterface $configurator): PartConfiguratorEvent
	{
		$this->configurator = $configurator;

		return $this;
	}

	/**
	 * Get configurator
	 *
	 * @return PartConfiguratorInterface
	 */
	public function getConfigurator(): PartConfiguratorInterface
	{
		return $this->configurator;
	}
}