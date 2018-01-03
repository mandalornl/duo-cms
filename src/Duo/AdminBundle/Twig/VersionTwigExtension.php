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
			new \Twig_SimpleFunction('get_revertable_versions', [$this, 'getRevertableVersions'])
		];
	}

	/**
	 * Get revertable versions
	 *
	 * @param VersionInterface $entity
	 *
	 * @return array
	 */
	public function getRevertableVersions(VersionInterface $entity): array
	{
		$entities = [];

		foreach ($entity->getVersions() as $version)
		{
			/**
			 * @var VersionInterface|DeleteInterface $version
			 */
			if ($version === $entity->getVersion() || ($version instanceof DeleteInterface && $version->isDeleted()))
			{
				continue;
			}

			$entities[] = $version;
		}

		return $entities;
	}
}