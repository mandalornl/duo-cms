<?php

namespace Duo\MediaBundle\DependencyInjection;

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
		$rootNode = $treeBuilder->root('duo_media');

		$rootNode
			->children()
				->scalarNode('relative_path')->defaultValue('/media')->end()
				->scalarNode('absolute_path')->defaultValue('%kernel.project_dir%/web/media')->end()
				->scalarNode('cache_prefix')->defaultValue('media/cache')->end()
			->end();

		return $treeBuilder;
	}
}
