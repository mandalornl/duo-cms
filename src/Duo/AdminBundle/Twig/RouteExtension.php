<?php

namespace Duo\AdminBundle\Twig;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class RouteExtension extends AbstractExtension
{
	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * RouteExtension constructor
	 *
	 * @param RouterInterface $router
	 */
	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTests(): array
	{
		return [
			new TwigTest('validroute', [$this, 'isValidRoute'])
		];
	}

	/**
	 * Is valid route
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isValidRoute(string $name): bool
	{
		return $this->router->getRouteCollection()->get($name) !== null;
	}
}
