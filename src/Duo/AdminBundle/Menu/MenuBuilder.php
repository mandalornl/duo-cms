<?php

namespace Duo\AdminBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\AdminBundle\Event\MenuEvent;
use Duo\AdminBundle\Event\MenuEvents;
use Duo\SecurityBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
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
	 * @var UserInterface
	 */
	private $user;

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
	 * @var MenuInterface
	 */
	private $activeMenu;

	/**
	 * MenuBuilder constructor
	 *
	 * @param RouterInterface $router
	 * @param EventDispatcherInterface $eventDispatcher
	 * @param RequestStack $requestStack
	 * @param Security $security
	 */
	public function __construct(RouterInterface $router,
								EventDispatcherInterface $eventDispatcher,
								RequestStack $requestStack,
								Security $security
	)
	{
		$this->router = $router;
		$this->eventDispatcher = $eventDispatcher;

		$this->user = $security->getUser();

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
	 * @param bool $rebuild [optional]
	 */
	public function build(bool $rebuild = false): void
	{
		if ($this->menu !== null && !$rebuild)
		{
			return;
		}

		$this->activeMenu = null;

		// dispatch pre build event
		$this->eventDispatcher->dispatch(MenuEvents::PRE_BUILD, new MenuEvent($this));

		$menu = (new Menu())
			->setLabel('Root')
			->setId('root');

		foreach ($this->configs as $config)
		{
			$this->parse($config, $menu);
		}

		foreach ($menu->getChildren() as $child)
		{
			/**
			 * @var MenuInterface $child
			 */
			$child->setParent(null);
		}

		$this->menu = $menu;

		$this->buildBreadcrumbs();

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
	private function parse(array $config, MenuInterface $parent): void
	{
		foreach ($config as $id => $item)
		{
			// check whether or not route is accessible by user
			if (isset($item['roles']) && ($this->user === null || !$this->user->hasRoles((array)$item['roles'])))
			{
				continue;
			}

			// check whether or not menu exists for parent's children
			if ($parent->getChildren()->containsKey($id))
			{
				$menu = $parent->getChildren()->get($id);
			}
			// create new item instead
			else
			{
				$menu = (new Menu())->setId($id);
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

			// set active menu item
			if (($url = $menu->getUrl()) && strcmp($url, $this->requestUri) === 0)
			{
				$menu->setActive(true);

				$this->activeMenu = $menu;
			}

			// contains children
			if (isset($item['children']))
			{
				$this->parse($item['children'], $menu);
			}

			$parent->addChild($menu);
		}
	}

	/**
	 * Build breadcrumbs
	 */
	private function buildBreadcrumbs(): void
	{
		if ($this->activeMenu === null)
		{
			return;
		}

		$breadcrumbs = [];

		$parent = $this->activeMenu;

		do
		{
			$breadcrumbs[] = $parent;

			$parent = $parent->getParent();
		}
		while ($parent !== null);

		$this->menu->setBreadcrumbs(array_reverse($breadcrumbs));
	}
}