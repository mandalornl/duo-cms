<?php

namespace Duo\TranslatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CacheCommand extends ContainerAwareCommand
{
	/**
	 * {@inheritdoc}
	 */
	protected function configure(): void
	{
		$this
			->setName('duo:translator:cache')
			->addOption('clear', null, InputOption::VALUE_NONE, 'Clear translation cache.');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Exception
	 */
	protected function execute(InputInterface $input, OutputInterface $output): void
	{
		if (!$input->getOption('clear'))
		{
			return;
		}

		$cacheDir = $this->getContainer()->getParameter('kernel.cache_dir');

		$finder = new Finder();
		$finder
			->files()
			->name('*.php')
			->name('*.meta')
			->in("{$cacheDir}/translations");

		foreach ($finder as $file)
		{
			unlink($file->getRealPath());
		}

		$timestamp = (new \DateTime())->format('Y-m-d H:i:s');

		$output->writeln("{$timestamp} - Cache cleared.");
	}
}
