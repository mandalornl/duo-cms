<?php

namespace Duo\PartBundle\Configurator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\PartBundle\Event\PartConfiguratorEvent;
use Duo\PartBundle\Event\PartConfiguratorEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Yaml\Yaml;

class PartConfigurator implements PartConfiguratorInterface
{
	/**
	 * @var Collection
	 */
	private $configs;

	/**
	 * @var EventDispatcherInterface
	 */
	private $eventDispatcher;

	/**
	 * PartConfigurator constructor
	 *
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function __construct(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;

		$this->configs = new ArrayCollection();

		// dispatch pre load event
		$eventDispatcher->dispatch(PartConfiguratorEvents::PRE_LOAD, new PartConfiguratorEvent($this));

		// add default config
		if (!count($this->configs))
		{
			$this->configs[] = Yaml::parseFile(__DIR__ . '/../Resources/config/parts.yml');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function addConfig(array $config): PartConfiguratorInterface
	{
		$this->configs[] = $config;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeConfig(array $config): PartConfiguratorInterface
	{
		$this->configs->removeElement($config);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfigs()
	{
		return $this->configs;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTypes(): array
	{
		$types = [];
		foreach ($this->configs as $entries)
		{
			foreach ($entries as $entry)
			{
				$types[$entry['class']] = $entry['label'];
			}
		}

		return $types;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIcons(): array
	{
		$icons = [];
		foreach ($this->configs as $entries)
		{
			foreach ($entries as $entry)
			{
				if (empty($entry['icon']))
				{
					continue;
				}

				$icons[$entry['class']] = $entry['icon'];
			}
		}

		return $icons;
	}
}