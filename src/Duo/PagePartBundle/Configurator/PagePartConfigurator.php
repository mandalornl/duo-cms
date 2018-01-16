<?php

namespace Duo\PagePartBundle\Configurator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\PagePartBundle\Event\PagePartConfiguratorEvent;
use Duo\PagePartBundle\Event\PagePartConfiguratorEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Yaml\Yaml;

class PagePartConfigurator implements PagePartConfiguratorInterface
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
	 * PagePartConfigurator constructor
	 *
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function __construct(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;

		$this->configs = new ArrayCollection();

		// dispatch pre load event
		$eventDispatcher->dispatch(PagePartConfiguratorEvents::PRE_LOAD, new PagePartConfiguratorEvent($this));

		// add default config
		if (!count($this->configs))
		{
			$this->configs[] = Yaml::parseFile(__DIR__ . '/../Resources/config/pageparts.yml');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function addConfig(array $config): PagePartConfiguratorInterface
	{
		$this->configs[] = $config;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeConfig(array $config): PagePartConfiguratorInterface
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