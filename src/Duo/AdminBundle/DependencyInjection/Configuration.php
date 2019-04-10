<?php

namespace Duo\AdminBundle\DependencyInjection;

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
	 * {@inheritDoc}
	 */
	public function getConfigTreeBuilder(): TreeBuilder
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('duo_admin');

		$rootNode
			->children()
				->scalarNode('locales')->isRequired()->defaultValue('en|nl')->end()
				->scalarNode('dkim_key_file')->defaultNull()->end()
				->scalarNode('dkim_domain')->defaultValue('example.com')->end()
				->scalarNode('dkim_selector')->defaultValue('default')->end()
			->end();

		return $treeBuilder;
	}
}
