<?php

namespace Duo\SeoBundle\Routing;

use Duo\SeoBundle\Entity\Redirect;
use Duo\SeoBundle\Repository\RedirectRepository;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RedirectLoader extends Loader
{
	/**
	 * @var bool
	 */
	private $isLoaded;

	/**
	 * @var RedirectRepository
	 */
	private $repository;

	/**
	 * RedirectLoader constructor
	 *
	 * @param RedirectRepository $repository
	 */
	public function __construct(RedirectRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function load($resource, $type = null): RouteCollection
	{
		if ($this->isLoaded === true)
		{
			$className = static::class;

			throw new \RuntimeException("Do not add the '{$className}' loader twice");
		}

		$this->isLoaded = true;

		$routes = new RouteCollection();

		foreach ($this->repository->findBy([
			'active' => true
		]) as $redirect)
		{
			$oid = spl_object_hash($redirect);

			/**
			 * @var Redirect $redirect
			 */
			$routes->add("_redirect_{$oid}", new Route($redirect->getOrigin(), [
				'_controller' => 'FrameworkBundle:Redirect:urlRedirect',
				'path' => $redirect->getTarget(),
				'permanent' => $redirect->getPermanent()
			]));
		}

		return $routes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function supports($resource, $type = null): bool
	{
		return $type === 'redirect';
	}
}