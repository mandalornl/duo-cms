<?php

namespace Duo\AdminBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\AdminBundle\Event\MenuEvent;
use Duo\AdminBundle\Event\MenuEvents;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;

class MenuBuilder
{
	/**
	 * @var Collection
	 */
	private $adapters;

	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * @var TraceableEventDispatcher
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
	 * @param TraceableEventDispatcher $eventDispatcher
	 * @param RequestStack $requestStack
	 */
	public function __construct(RouterInterface $router,
								TraceableEventDispatcher $eventDispatcher,
								RequestStack $requestStack)
	{
		$this->router = $router;
		$this->eventDispatcher = $eventDispatcher;

		$this->adapters = new ArrayCollection();
		$this->adapters[] = Yaml::parseFile(__DIR__ . '/../Resources/config/menu.yml');

		// cache request uri
		if (($request = $requestStack->getCurrentRequest()))
		{
			$this->requestUri = $request->getRequestUri();
		}

		// dispatch pre load event
		$eventDispatcher->dispatch(MenuEvents::PRE_LOAD, new MenuEvent($this));
	}

	/**
	 * Add adapter
	 *
	 * @param array $adapter
	 *
	 * @return MenuBuilder
	 */
	public function addAdapter(array $adapter): MenuBuilder
	{
		$this->adapters[] = $adapter;

		return $this;
	}

	/**
	 * Remove adapter
	 *
	 * @param array $adapter
	 *
	 * @return MenuBuilder
	 */
	public function removeAdapter(array $adapter): MenuBuilder
	{
		$this->adapters->removeElement($adapter);

		return $this;
	}

	/**
	 * Get adapters
	 *
	 * @return ArrayCollection
	 */
	public function getAdapters()
	{
		return $this->adapters;
	}

	/**
	 * Set menu
	 *
	 * @param MenuInterface $menu
	 *
	 * @return MenuBuilder
	 */
	public function setMenu(MenuInterface $menu): MenuBuilder
	{
		$this->menu = $menu;

		return $this;
	}

	/**
	 * Get menu
	 *
	 * @return MenuInterface
	 */
	public function getMenu(): MenuInterface
	{
		return $this->menu;
	}

	/**
	 * Build
	 *
	 * @param bool $rebuild [optional]
	 */
	public function build(bool $rebuild = false): void
	{
		if ($this->menu !== null && !$rebuild)
		{
			return;
		}

		// dispatch pre build event
		$this->eventDispatcher->dispatch(MenuEvents::PRE_BUILD, new MenuEvent($this));

		$menu = (new Menu())
			->setLabel('Root')
			->setUniqid('root');

		foreach ($this->adapters as $config)
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
	 * Create view
	 *
	 * @return MenuInterface
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
		foreach ($config as $uniqid => $item)
		{
			// check whether or not menu exists for parent's children
			if ($parent->getChildren()->containsKey($uniqid))
			{
				$menu = $parent->getChildren()->get($uniqid);
			}
			// create new item instead
			else
			{
				$menu = (new Menu())
					->setUniqid($uniqid);
			}

			if (isset($item['label']))
			{
				$menu->setLabel($item['label']);
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