<?php

namespace Duo\SeoBundle\Twig;

use Duo\NodeBundle\Entity\NodeInterface;

class SeoTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('render_seo_metadata', [$this, 'renderSeoMetadata'], [
				'needs_environment' => true,
				'is_safe' => ['html']
			])
		];
	}

	/**
	 * Render seo metadata
	 *
	 * @param \Twig_Environment $env
	 * @param NodeInterface $page
	 * @param array $parameters
	 *
	 * @return string
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function renderSeoMetadata(\Twig_Environment $env, NodeInterface $page, array $parameters = []): string
	{
		$template = $env->load('@DuoSeo/Seo/metadata.html.twig');

		return $template->render(array_merge([
			'page' => $page
		], $parameters));
	}
}