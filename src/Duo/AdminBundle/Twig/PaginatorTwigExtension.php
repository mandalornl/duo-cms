<?php

namespace Duo\AdminBundle\Twig;

use Duo\AdminBundle\Helper\PaginatorHelper;

class PaginatorTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('render_paginator', [$this, 'renderPaginator'], [
				'needs_environment' => true,
				'is_safe' => ['html']
			])
		];
	}

	/**
	 * Render paginator
	 *
	 * @param \Twig_Environment $env
	 * @param PaginatorHelper $paginator
	 * @param string $routeName
	 * @param array $routeParameters [optional]
	 * @param array $parameters [optional]
	 *
	 * @return string
	 *
	 * @throws \Throwable
	 */
	public function renderPaginator(\Twig_Environment $env,
									 PaginatorHelper $paginator,
									 string $routeName,
									 array $routeParameters = [],
									 array $parameters = []): string
	{
		$template = $env->load('@DuoAdmin/Navigation/pagination.html.twig');

		return $template->render(array_merge([
			'paginator' => $paginator,
			'routeName' => $routeName,
			'routeParameters' => $routeParameters
		], $parameters));
	}
}