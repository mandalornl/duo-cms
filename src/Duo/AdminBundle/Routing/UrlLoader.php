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
	 * {@inheritDoc}
	 */
	public function load($resource, $type = null): RouteCollection
	{
		if ($this->loaded === true)
		{
			$className = static::class;

			throw new \RuntimeException("Do not add the '{$className}' loader twice");
		}

		$this->loaded = true;

		$routes = new RouteCollection();

		$this->addUrlRoute($routes);
		$this->addRedirectToPreferredLanguageRoute($routes);

		return $routes;
	}

	/**
	 * {@inheritDoc}
	 */
	public function supports($resource, $type = null): bool
	{
		return $type === 'url';
	}

	/**
	 * Add url route
	 *
	 * @param RouteCollection $routes
	 */
	private function addUrlRoute(RouteCollection $routes): void
	{
		$slugPattern = '([\w\-\/]+)?';

		if (count($this->localeHelper->getLocales()) > 1)
		{
			$routes->add('_url', new Route('/{_locale}/{url}', [
				'_controller' => 'AppBundle:Default:index',
				'_locale' => $this->localeHelper->getLocale()
			], [
				'url' => $slugPattern,
				'_locale' => $this->localeHelper->getLocalesAsString()
			]));

			return;
		}

		$routes->add('_url', new Route('/{url}', [
			'_controller' => 'AppBundle:Default:index'
		], [
			'url' => $slugPattern
		]));
	}

	/**
	 * Add redirect to preferred language route
	 *
	 * @param RouteCollection $routes
	 */
	private function addRedirectToPreferredLanguageRoute(RouteCollection $routes): void
	{
		if (count($this->localeHelper->getLocales()) <= 1)
		{
			return;
		}

		$routes->add('_redirect_to_preferred_language', new Route('/', [
			'_controller' => 'FrameworkBundle:Redirect:redirect',
			'route' => '_url',
			'url' => '',
			'_locale' => $this->localeHelper->getPreferredLanguage()
		]));
	}
}
