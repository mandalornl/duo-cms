<?php

namespace Duo\AdminBundle\Routing;

use Duo\AdminBundle\Helper\LocaleHelper;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class UrlLoader extends Loader
{
	/**
	 * @var bool
	 */
	private $loaded;

	/**
	 * @var string
	 */
	private $routeName = 'url';

	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * UrlLoader constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function load($resource, $type = null): RouteCollection
	{
		if ($this->loaded === true)
		{
			$className = get_class($this);

			throw new \RuntimeException("Do not add the '{$className}' loader twice");
		}

		$this->loaded = true;

		$routes = new RouteCollection();
		$routes->add($this->routeName, $this->getRoute());

		return $routes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function supports($resource, $type = null): bool
	{
		return $type === $this->routeName;
	}

	/**
	 * Get route
	 *
	 * @return Route
	 */
	private function getRoute(): Route
	{
		if (count($this->localeHelper->getLocales()) > 1)
		{
			return new Route('/{_locale}/{url}', [
				'_controller' => 'DuoAdminBundle:Url:index',
				'_locale' => $this->localeHelper->getLocale()
			], [
				'url' => '(.+)?',
				'_locale' => $this->localeHelper->getLocalesAsString()
			]);
		}

		return new Route('/{url}', [
			'_controller' => 'DuoAdminBundle:Url:index'
		], [
			'url' => '(.+)?'
		]);
	}
}