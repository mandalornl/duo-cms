<?php

namespace Duo\AdminBundle\Twig;

use Duo\AdminBundle\Tools\Pagination\Paginator;

class PaginatorTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('render_paginator', [$this, 'renderPaginator'], [
				'needs_environment' => true,
				'is_safe' => ['html']
			]),

			new \Twig_SimpleFunction('render_paginator_limiter', [$this, 'renderPaginatorLimiter'], [
				'needs_environment' => true,
				'is_safe' => ['html']
			])
		];
	}

	/**
	 * Render paginator
	 *
	 * @param \Twig_Environment $env
	 * @param Paginator $paginator
	 * @param string $routeName
	 * @param array $routeParameters [optional]
	 * @param array $parameters [optional]
	 *
	 * @return string
	 *
	 * @throws \Throwable
	 */
	public function renderPaginator(
		\Twig_Environment $env,
		Paginator $paginator,
		string $routeName,
		array $routeParameters = [],
		array $parameters = []
	): string
	{
		$template = $env->load('@DuoAdmin/Navigation/paginator.html.twig');

		return $template->render(array_merge([
			'paginator' => $paginator,
			'routeName' => $routeName,
			'routeParameters' => $routeParameters
		], $parameters));
	}

	/**
	 * Render paginator limiter
	 *
	 * @param \Twig_Environment $env
	 * @param Paginator $paginator
	 * @param string $routeName
	 * @param array $routeParameters [optional]
	 * @param array $parameters [optional]
	 *
	 * @return string
	 *
	 * @throws \Throwable
	 */
	public function renderPaginatorLimiter(
		\Twig_Environment $env,
		Paginator $paginator,
		string $routeName,
		array $routeParameters,
		array $parameters = []
	): string
	{
		$template = $env->load('@DuoAdmin/Navigation/paginator_limiter.html.twig');

		return $template->render(array_merge([
			'paginator' => $paginator,
			'routeName' => $routeName,
			'routeParameters' => $routeParameters
		], $parameters));
	}
}
