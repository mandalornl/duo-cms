<?php

namespace Duo\PartBundle\Event;

use Duo\PartBundle\Configurator\PartConfiguratorInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractPartConfiguratorEvent extends Event implements PartConfigurationEventInterface
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
	 * {@inheritdoc}
	 */
	public function setConfigurator(PartConfiguratorInterface $configurator): PartConfigurationEventInterface
	{
		$this->configurator = $configurator;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfigurator(): PartConfiguratorInterface
	{
		return $this->configurator;
	}
}