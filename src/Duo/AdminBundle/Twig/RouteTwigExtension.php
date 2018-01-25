<?php

namespace Duo\AdminBundle\Twig;

use Symfony\Component\Routing\RouterInterface;

class RouteTwigExtension extends \Twig_Extension
{
	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * RouteTwigExtension constructor
	 *
	 * @param RouterInterface $router
	 */
	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTests(): array
	{
		return [
			new \Twig_SimpleTest('validroute', [$this, 'isValidRoute'])
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