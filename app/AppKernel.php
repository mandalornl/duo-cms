<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = [
			new Duo\AdminBundle\DuoAdminBundle(),
            new Duo\CoreBundle\DuoCoreBundle(),
            new Duo\FormBundle\DuoFormBundle(),
			new Duo\MediaBundle\DuoMediaBundle(),
            new Duo\PageBundle\DuoPageBundle(),
            new Duo\PartBundle\DuoPartBundle(),
            new Duo\SecurityBundle\DuoSecurityBundle(),
            new Duo\SeoBundle\DuoSeoBundle(),
            new Duo\TaxonomyBundle\DuoTaxonomyBundle(),
			new Duo\TranslatorBundle\DuoTranslatorBundle(),

			new AppBundle\AppBundle(),

			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),

			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

			new Infinite\FormBundle\InfiniteFormBundle(),

			new Liip\ImagineBundle\LiipImagineBundle(),

			new NotFloran\MjmlBundle\MjmlBundle(),

			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
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
