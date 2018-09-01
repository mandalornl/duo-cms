<?php

namespace AppBundle\EventListener;

use Duo\FormBundle\Event\FormPartConfiguratorEvent;
use Duo\FormBundle\Event\FormPartConfiguratorEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

class FormPartConfiguratorListener implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			FormPartConfiguratorEvents::PRE_LOAD => 'preLoad'
		];
	}

	/**
	 * On pre load event
	 *
	 * @param FormPartConfiguratorEvent $event
	 */
	public function preLoad(FormPartConfiguratorEvent $event): void
	{
		$config = Yaml::parseFile(__DIR__ . '/../Resources/config/parts/form.yml');

		$event->getConfigurator()->addConfig($config);
	}
}