<?php

namespace Duo\FormBundle\Configurator;

use Duo\FormBundle\Event\FormPartConfiguratorEvent;
use Duo\FormBundle\Event\FormPartConfiguratorEvents;
use Duo\PartBundle\Configurator\AbstractPartConfigurator;

class FormPartConfigurator extends AbstractPartConfigurator
{
	/**
	 * {@inheritdoc}
	 */
	public function dispatchPreLoadEvent(): void
	{
		$this->eventDispatcher->dispatch(FormPartConfiguratorEvents::PRE_LOAD, new FormPartConfiguratorEvent($this));
	}
}