<?php

namespace Duo\AdminBundle\Routing;

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
	 * @var string
	 */
	private $locale = 'nl';

	/**
	 * UrlLoader constructor
	 *
	 * @param string $locale
	 */
	public function __construct(string $locale)
	{
		$this->locale = $locale;
	}

	/**
	 * {@inheritdoc}
	 */
	public function load($resource, $type = null)
	{
		if ($this->loaded === true)
		{
			$className = get_class($this);
			throw new \RuntimeException("Do not add the '{$className}' loader twice");
		}

		$this->loaded = true;

		$path = '/{_locale}/{url}';
		$defaults = [
			'_controller' => 'DuoAdminBundle:Url:index',
			'_locale' => $this->locale
		];
		$requirements = [
			'url' => '.+'
		];

		$routes = new RouteCollection();
		$routes->add($this->routeName, new Route($path, $defaults, $requirements));

		return $routes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function supports($resource, $type = null): bool
	{
		return $type === $this->routeName;
	}
}