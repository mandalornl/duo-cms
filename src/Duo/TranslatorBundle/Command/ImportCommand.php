<?php

namespace Duo\TranslatorBundle\Command;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Duo\TranslatorBundle\Entity\Entry;
use Duo\TranslatorBundle\Entity\EntryTranslation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ImportCommand extends ContainerAwareCommand
{
	/**
	 * {@inheritdoc}
	 */
	protected function configure(): void
	{
		$this
			->setName('duo:translator:import')
			->setDescription('Import translator entries.')
			->setHelp('The <info>duo:translator:import</info> command will iterate all <info>[domain].[locale].db</info> translation files and import their contents to the database.')
			->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite existing database entries.')
			->addOption('clear', 'c', InputOption::VALUE_NONE, 'Remove existing entries from database.')
			->addOption('domain', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'The domain to use, e.g. <fg=cyan>messages</>.')
			->addOption('locale', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'The locale to use. Defaults to <fg=cyan>%locales%</>.')
			->addOption('file', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'The file to use relative to <fg=cyan>%kernel.project_dir%</>.')
			->addOption('directory', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'The directory to use relative to <fg=cyan>%kernel.project_dir%</>.', [
				'app/Resources',
				'src'
			])
			->addOption('batch-size', 'b', InputOption::VALUE_OPTIONAL, 'The batch size to use.', 100);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output): void
	{
		$io = new SymfonyStyle($input, $output);

		if ($input->getOption('clear') && $io->confirm('Are you sure you want to clear all entries?'))
		{
			try
			{
				$this->truncate($output);

				$output->writeln("{$this->getTimestamp()} - Database cleared.");
			}
			catch (ConnectionException $e)
			{
				$io->warning($e->getMessage());
			}

			return;
		}

		$manager = $this->getManager();
		$repository = $manager->getRepository(Entry::class);

		$references = $this->getReferences();
		$messages = $this->getMessages($input);

		$count = 1;
		$batchSize = $input->getOption('batch-size');

		foreach ($messages as $domain => $keywords)
		{
			foreach ($keywords as $keyword => $locales)
			{
				$referenceId = "{$domain}.{$keyword}";

				$entity = null;

				if (isset($references[$referenceId]))
				{
					if (!$input->getOption('force'))
					{
						$output->writeln("{$this->getTimestamp()} - Entry for '{$domain}.[locale] - {$keyword}' already exists.");
						continue;
					}

					$entity = $repository->find($references[$referenceId]);
				}

				if ($entity === null)
				{
					$entity = (new Entry())
						->setDomain($domain)
						->setKeyword($keyword);
				}

				foreach ($locales as $locale => $text)
				{
					$entity->translate($locale)->setText($text);

					$output->writeln("{$this->getTimestamp()} - Entry for '{$domain}.{$locale} - {$keyword}' added.");
				}

				$entity->mergeNewTranslations();

				$manager->persist($entity);

				if (($count++) % $batchSize === 0)
				{
					$manager->flush();
					$manager->clear();

					$output->writeln("{$this->getTimestamp()} - Batch complete.");
				}
			}
		}

		$output->writeln("{$this->getTimestamp()} - Flush remaining entities.");

		$manager->flush();
		$manager->clear();

		$output->writeln("{$this->getTimestamp()} - Job finished.");
	}

	/**
	 * Get locales
	 *
	 * @param InputInterface $input
	 *
	 * @return string[]
	 */
	private function getLocales(InputInterface $input): array
	{
		return $input->getOption('locale') ?: explode('|', $this->getContainer()->getParameter('locales'));
	}

	/**
	 * Get files
	 *
	 * @param InputInterface $input
	 *
	 * @return string[]
	 */
	private function getFiles(InputInterface $input): array
	{
		if (count($input->getOption('file')))
		{
			$projectDir = $this->getContainer()->getParameter('kernel.project_dir');

			$fs = new FileSystem();

			$files = array_filter($input->getOption('file'), function(string $filename) use ($projectDir, $fs)
			{
				return $fs->exists("{$projectDir}/{$filename}");
			});

			return array_map(function(string $filename) use ($projectDir)
			{
				return "{$projectDir}/{$filename}";
			}, $files);
		}

		$dirs = $this->getDirectories($input);

		$locales = implode('|', $this->getLocales($input));

		$pattern = "\.({$locales})\.db$";

		if (count($domains = $input->getOption('domain')))
		{
			$domains = implode('|', $domains);

			$pattern = "^({$domains}){$pattern}";
		}

		$finder = new Finder();
		$finder->files()->name("/{$pattern}/")->in($dirs);

		$files = [];

		foreach ($finder as $file)
		{
			$files[] = $file->getRealPath();
		}

		return $files;
	}

	/**
	 * Get directories
	 *
	 * @param InputInterface $input
	 *
	 * @return string[]
	 */
	private function getDirectories(InputInterface $input): array
	{
		$projectDir = $this->getContainer()->getParameter('kernel.project_dir');

		$fs = new Filesystem();

		$dirs = array_filter($input->getOption('directory'), function(string $dir) use ($projectDir, $fs)
		{
			return $fs->exists("{$projectDir}/{$dir}");
		});

		return array_map(function(string $dir) use ($projectDir)
		{
			return "{$projectDir}/{$dir}";
		}, $dirs);
	}

	/**
	 * Get messages
	 *
	 * @param InputInterface $input
	 *
	 * @return string[]
	 */
	private function getMessages(InputInterface $input): array
	{
		$files = $this->getFiles($input);

		$result = [];

		foreach ($files as $filename)
		{
			list($domain, $locale) = explode('.', basename($filename));

			$messages = $this->flattenArray(Yaml::parseFile($filename));

			foreach ($messages as $keyword => $text)
			{
				$result[$domain][$keyword][$locale] = $text;
			}
		}

		return $result;
	}

	/**
	 * Get manager
	 *
	 * @return EntityManagerInterface
	 */
	private function getManager(): EntityManagerInterface
	{
		return $this->getContainer()->get('doctrine')->getManager();
	}

	/**
	 * Truncate
	 *
	 * @param OutputInterface $output
	 *
	 * @return bool
	 *
	 * @throws ConnectionException
	 */
	private function truncate(OutputInterface $output): bool
	{
		$manager = $this->getManager();

		$connection = $manager->getConnection();

		try
		{
			$oldForeignKeyChecks = (int)$connection
				->query('SELECT @@FOREIGN_KEY_CHECKS')
				->fetchColumn();

			$platform = $connection->getDatabasePlatform();

			$connection->beginTransaction();
			$connection->query('SET FOREIGN_KEY_CHECKS=0');

			foreach ([
				 Entry::class,
				 EntryTranslation::class
			 ] as $className)
			{
				$connection->query($platform->getTruncateTableSQL($manager->getClassMetadata($className)->getTableName()));
			}

			$connection->query("SET FOREIGN_KEY_CHECKS={$oldForeignKeyChecks}");
			$connection->commit();

			return true;
		}
		catch (DBALException $e)
		{
			$manager->close();

			$connection->rollBack();

			$output->writeln($e->getMessage());
		}

		return false;
	}

	/**
	 * Flatten array
	 *
	 * @param array $input
	 * @param string $prefix [optional]
	 *
	 * @return string[]
	 */
	private function flattenArray(array $input, string $prefix = ''): array
	{
		$result = [];

		foreach ($input as $key => $value)
		{
			$newKey = $prefix . ($prefix ? '.' : '') . $key;

			if (is_array($value))
			{
				$result = array_merge($result, $this->flattenArray($value, $newKey));

				continue;
			}

			$result[$newKey] = $value;
		}

		return $result;
	}

	/**
	 * Get references
	 *
	 * @return int[]
	 */
	private function getReferences(): array
	{
		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->getManager()->getRepository(Entry::class);

		$result = $repository
			->createQueryBuilder('e')
			->select('e.id, e.domain, e.keyword')
			->getQuery()
			->getScalarResult();

		foreach ($result as $index => $row)
		{
			$result["{$row['domain']}.{$row['keyword']}"] = (int)$row['id'];

			unset($result[$index]);
		}

		return $result;
	}

	/**
	 * Get timestamp
	 *
	 * @return string
	 */
	private function getTimestamp(): string
	{
		return (new \DateTime())->format('Y-m-d H:i:s');
	}
}