<?php

namespace Duo\FormBundle\Configurator;

use Duo\FormBundle\Event\FormPartConfiguratorEvent;
use Duo\FormBundle\Event\FormPartConfiguratorEvents;
use Duo\PartBundle\Configurator\AbstractPartConfigurator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Yaml\Yaml;

class FormPartConfigurator extends AbstractPartConfigurator
{
	/**
	 * FormPartConfigurator constructor
	 *
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function __construct(EventDispatcherInterface $eventDispatcher)
	{
		parent::__construct($eventDispatcher);

		if (!count($this->configs))
		{
			$config = Yaml::parseFile(__DIR__ . '/../Resources/config/formparts.yml');
			$this->addConfig($config);
		}
	}

	/**
	 * Dispatch pre load event
	 */
	public function dispatchPreLoadEvent(): void
	{
		$this->eventDispatcher->dispatch(FormPartConfiguratorEvents::PRE_LOAD, new FormPartConfiguratorEvent($this));
	}
}