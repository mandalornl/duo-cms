<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = [
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new Infinite\FormBundle\InfiniteFormBundle(),
			new NotFloran\MjmlBundle\MjmlBundle(),
            new Duo\CoreBundle\DuoCoreBundle(),
            new Duo\PartBundle\DuoPartBundle(),
            new Duo\NodeBundle\DuoNodeBundle(),
            new Duo\PageBundle\DuoPageBundle(),
            new Duo\FormBundle\DuoFormBundle(),
            new Duo\SecurityBundle\DuoSecurityBundle(),
            new Duo\SeoBundle\DuoSeoBundle(),
            new Duo\TaxonomyBundle\DuoTaxonomyBundle(),
			new Duo\MediaBundle\DuoMediaBundle(),
			new Duo\TranslatorBundle\DuoTranslatorBundle(),
			new Duo\AdminBundle\DuoAdminBundle(),
			new AppBundle\AppBundle(),
        ];

		if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
			$bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
			$bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();

			if ('dev' === $this->getEnvironment()) {
				$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
				$bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
			}
		}

		return $bundles;
	}

	public function getRootDir()
	{
		return __DIR__;
	}

	public function getCacheDir()
	{
		return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
	}

	public function getLogDir()
	{
		return dirname(__DIR__) . '/var/logs';
	}

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
	}
}
