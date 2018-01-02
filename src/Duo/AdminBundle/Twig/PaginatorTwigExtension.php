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
			new \Twig_SimpleFunction('paginator_widget', [$this, 'render_paginator'], [
				'needs_environment' => true,
				'is_safe' => ['html' => true]
			])
		];
	}

	/**
	 * Render paginator
	 *
	 * @param \Twig_Environment $env
	 * @param PaginatorHelper $paginator
	 * @param string $url
	 * @param array $parameters [optional]
	 *
	 * @return string
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function render_paginator(\Twig_Environment $env, PaginatorHelper $paginator, string $url, array $parameters = []): string
	{
		$template = $env->load('@DuoAdmin/Navigation/pagination.html.twig');

		return $template->render(array_merge([
			'paginator' => $paginator,
			'url' => $url
		], $parameters));
	}
}