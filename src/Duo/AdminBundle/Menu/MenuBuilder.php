<?php

namespace Duo\AdminBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\AdminBundle\Event\MenuEvent;
use Duo\AdminBundle\Event\MenuEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;

class MenuBuilder implements MenuBuilderInterface
{
	/**
	 * @var Collection
	 */
	private $configs;

	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * @var EventDispatcherInterface
	 */
	private $eventDispatcher;

	/**
	 * @var string
	 */
	private $requestUri;

	/**
	 * @var MenuInterface
	 */
	private $menu;

	/**
	 * MenuBuilder constructor
	 *
	 * @param RouterInterface $router
	 * @param EventDispatcherInterface $eventDispatcher
	 * @param RequestStack $requestStack
	 */
	public function __construct(RouterInterface $router,
								EventDispatcherInterface $eventDispatcher,
								RequestStack $requestStack)
	{
		$this->router = $router;
		$this->eventDispatcher = $eventDispatcher;

		$this->configs = new ArrayCollection();
		$this->configs[] = Yaml::parseFile(__DIR__ . '/../Resources/config/menu.yml');

		// cache request uri
		if (($request = $requestStack->getCurrentRequest()))
		{
			$this->requestUri = $request->getRequestUri();
		}

		// dispatch pre load event
		$eventDispatcher->dispatch(MenuEvents::PRE_LOAD, new MenuEvent($this));
	}

	/**
	 * {@inheritdoc}
	 */
	public function addConfig(array $config): MenuBuilderInterface
	{
		$this->configs[] = $config;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeConfig(array $config): MenuBuilderInterface
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
	public function setMenu(MenuInterface $menu): MenuBuilderInterface
	{
		$this->menu = $menu;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMenu(): MenuInterface
	{
		return $this->menu;
	}

	/**
	 * Build
	 *
	 * @param bool $forceRebuild [optional]
	 */
	public function build(bool $forceRebuild = false): void
	{
		if ($this->menu !== null && !$forceRebuild)
		{
			return;
		}

		// dispatch pre build event
		$this->eventDispatcher->dispatch(MenuEvents::PRE_BUILD, new MenuEvent($this));

		$menu = (new Menu())
			->setLabel('Root')
			->setUniqid('root');

		foreach ($this->configs as $config)
		{
			$this->parseConfig($config, $menu);
		}

		foreach ($menu->getChildren() as $child)
		{
			/**
			 * @var MenuInterface $child
			 */
			$child->setParent(null);
		}

		$this->menu = $menu;

		// dispatch post build event
		$this->eventDispatcher->dispatch(MenuEvents::POST_BUILD, new MenuEvent($this));
	}

	/**
	 * {@inheritdoc}
	 */
	public function createView(): MenuInterface
	{
		$this->build();

		return $this->menu;
	}

	/**
	 * Parse config
	 *
	 * @param array $config
	 *
	 * @param MenuInterface $parent
	 */
	private function parseConfig(array $config, MenuInterface $parent): void
	{
		foreach ($config as $id => $item)
		{
			// check whether or not menu exists for parent's children
			if ($parent->getChildren()->containsKey($id))
			{
				$menu = $parent->getChildren()->get($id);
			}
			// create new item instead
			else
			{
				$menu = (new Menu())
					->setUniqid($id);
			}

			// set label
			if (isset($item['label']))
			{
				$menu->setLabel($item['label']);
			}

			// set icon
			if (isset($item['icon']))
			{
				$menu->setIcon($item['icon']);
			}

			// generate url from route
			if (isset($item['route']))
			{
				$routeParameters = [];
				if (isset($item['routeParameters']))
				{
					$routeParameters = $item['routeParameters'];
				}

				$menu->setUrl($this->router->generate($item['route'], $routeParameters));
			}
			else
			{
				// use given url instead
				if (isset($item['url']))
				{
					$menu->setUrl($item['url']);
				}
			}

			// contains children
			if (isset($item['children']))
			{
				$this->parseConfig($item['children'], $menu);
			}

			// set active menu item
			if (($url = $menu->getUrl()) &&
				(strcmp($url, $this->requestUri) === 0 || strpos($this->requestUri, $url) === 0))
			{
				$menu->setActive(true);
			}

			$parent->addChild($menu);
		}
	}
}