<?php

namespace Duo\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class DuoMediaExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('duo.media.relative_upload_path', $config['relative_upload_path']);
        $container->setParameter('duo.media.absolute_upload_path', $config['absolute_upload_path']);
    }

	/**
	 * {@inheritdoc}
	 */
	public function prepend(ContainerBuilder $container): void
	{
		$config = Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/imagine_filter_sets.yml'));
		$container->prependExtensionConfig('liip_imagine', $config['liip_imagine']);
	}
}