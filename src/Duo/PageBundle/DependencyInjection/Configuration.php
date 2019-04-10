<?php

namespace Duo\PageBundle\DependencyInjection;

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
		$rootNode = $treeBuilder->root('duo_page');

		$rootNode
			->children()
				->scalarNode('entity_class')->isRequired()->cannotBeEmpty()->end()
				->scalarNode('entity_translation_class')->isRequired()->cannotBeEmpty()->end()
			->end();

		return $treeBuilder;
	}
}
