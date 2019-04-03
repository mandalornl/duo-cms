<?php

namespace Duo\SeoBundle\Routing;

use Doctrine\ORM\EntityManagerInterface;
use Duo\SeoBundle\Entity\Redirect;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RedirectLoader extends Loader
{
	/**
	 * @var bool
	 */
	private $loaded;

	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * RedirectLoader constructor
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
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

		foreach ($this->manager->getRepository(Redirect::class)->findBy([
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
				'permanent' => $redirect->isPermanent()
			]));
		}

		return $routes;
	}

	/**
	 * {@inheritDoc}
	 */
	public function supports($resource, $type = null): bool
	{
		return $type === 'redirect';
	}
}
