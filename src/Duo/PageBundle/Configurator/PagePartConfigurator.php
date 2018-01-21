<?php

namespace Duo\PageBundle\Configurator;

use Duo\PageBundle\Event\PagePartConfiguratorEvent;
use Duo\PageBundle\Event\PagePartConfiguratorEvents;
use Duo\PartBundle\Configurator\AbstractPartConfigurator;

class PagePartConfigurator extends AbstractPartConfigurator
{
	/**
	 * Dispatch pre load event
	 */
	public function dispatchPreLoadEvent(): void
	{
		$this->eventDispatcher->dispatch(PagePartConfiguratorEvents::PRE_LOAD, new PagePartConfiguratorEvent($this));
	}
}