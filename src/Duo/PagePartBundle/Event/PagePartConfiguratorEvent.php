<?php

namespace Duo\PagePartBundle\Event;

use Duo\PagePartBundle\Configurator\PagePartConfiguratorInterface;
use Symfony\Component\EventDispatcher\Event;

class PagePartConfiguratorEvent extends Event
{
	/**
	 * @var PagePartConfiguratorInterface
	 */
	private $configurator;

	/**
	 * PagePartConfiguratorEvent constructor
	 *
	 * @param PagePartConfiguratorInterface $configurator
	 */
	public function __construct(PagePartConfiguratorInterface $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * Set configurator
	 *
	 * @param PagePartConfiguratorInterface $configurator
	 *
	 * @return PagePartConfiguratorEvent
	 */
	public function setConfigurator(PagePartConfiguratorInterface $configurator): PagePartConfiguratorEvent
	{
		$this->configurator = $configurator;

		return $this;
	}

	/**
	 * Get configurator
	 *
	 * @return PagePartConfiguratorInterface
	 */
	public function getConfigurator(): PagePartConfiguratorInterface
	{
		return $this->configurator;
	}
}