<?php

namespace Duo\AdminBundle\Twig;

use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;

class VersionTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('get_versions', [$this, 'getVersions'])
		];
	}

	/**
	 * Get versions
	 *
	 * @param VersionInterface $entity
	 *
	 * @return array
	 */
	public function getVersions(VersionInterface $entity): array
	{
		$entities = [];

		foreach ($entity->getVersions() as $version)
		{
			/**
			 * @var VersionInterface $version
			 */
			if ($version !== $entity->getVersion())
			{
				if ($version instanceof DeleteInterface && $version->isDeleted())
				{
					continue;
				}

				$entities[] = $version;
			}
		}

		return $entities;
	}
}