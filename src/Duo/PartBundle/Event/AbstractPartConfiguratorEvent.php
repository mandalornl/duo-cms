<?php

namespace Duo\PartBundle\Event;

use Duo\PartBundle\Configurator\PartConfiguratorInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractPartConfiguratorEvent extends Event
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
	 * @return AbstractPartConfiguratorEvent
	 */
	public function setConfigurator(PartConfiguratorInterface $configurator): AbstractPartConfiguratorEvent
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