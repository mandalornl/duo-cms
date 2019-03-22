<?php

namespace Duo\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder(): TreeBuilder
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('duo_form');

		$rootNode
			->children()
				->scalarNode('recaptcha_key')->isRequired()->end()
				->scalarNode('recaptcha_secret')->isRequired()->end()
			->end();

		return $treeBuilder;
	}
}
