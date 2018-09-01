<?php

namespace Duo\PartBundle\Configurator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractPartConfigurator implements PartConfiguratorInterface
{
	/**
	 * @var Collection
	 */
	protected $configs;

	/**
	 * @var EventDispatcherInterface
	 */
	protected $eventDispatcher;

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
		$this->dispatchPreLoadEvent();
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
	public function getConfigs(): Collection
	{
		return $this->configs;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTypes(array $ids = []): array
	{
		$types = [];

		foreach ($this->configs as $config)
		{
			foreach ($config['types'] as $id => $entry)
			{
				if (!empty($ids) && !in_array($id, $ids))
				{
					continue;
				}

				$types[$entry['type']] = $entry['label'];
			}
		}

		return $types;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSections(): array
	{
		$sections = [];

		foreach ($this->configs as $config)
		{
			foreach ($config['sections'] as $id => $entry)
			{
				$sections[$id] = [
					'label' => $entry['label'],
					'types' => $this->getTypes($entry['types'])
				];
			}
		}

		return $sections;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIcons(): array
	{
		$icons = [];

		foreach ($this->configs as $config)
		{
			foreach ($config['types'] as $entry)
			{
				if (empty($entry['icon']))
				{
					continue;
				}

				$icons[$entry['type']] = $entry['icon'];
			}
		}

		return $icons;
	}
}