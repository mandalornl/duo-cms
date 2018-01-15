<?php

namespace Duo\AdminBundle\Routing;

use Doctrine\Common\Util\ClassUtils;
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
	public function load($resource, $type = null)
	{
		if ($this->loaded === true)
		{
			$className = ClassUtils::getClass($this);
			throw new \RuntimeException("Do not add the '{$className}' loader twice");
		}

		$this->loaded = true;

		$path = '/{_locale}/{url}';
		$defaults = [
			'_controller' => 'DuoAdminBundle:Url:index',
			'_locale' => $this->localeHelper->getLocale()
		];
		$requirements = [
			'url' => '(.+)?',
			'_locale' => implode('|', $this->localeHelper->getLocales())
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