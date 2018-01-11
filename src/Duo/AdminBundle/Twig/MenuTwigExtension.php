<?php

namespace Duo\AdminBundle\Twig;

use Duo\AdminBundle\Menu\MenuInterface;

class MenuTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('render_menu', [$this, 'renderMenu'], [
				'needs_environment' => true,
				'is_safe' => ['html']
			])
		];
	}

	/**
	 * Render menu
	 *
	 * @param \Twig_Environment $env
	 * @param MenuInterface $menu
	 * @param array $parameters
	 * @return string
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function renderMenu(\Twig_Environment $env, MenuInterface $menu, array $parameters = []): string
	{
		$template = $env->load('@DuoAdmin/Navigation/menu.html.twig');

		return $template->render(array_merge([
			'menu' => $menu
		], $parameters));
	}
}