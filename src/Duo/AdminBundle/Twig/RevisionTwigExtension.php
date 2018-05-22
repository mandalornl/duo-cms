<?php

namespace Duo\AdminBundle\Twig;

use Duo\CoreBundle\Entity\DeleteInterface;
use Duo\CoreBundle\Entity\RevisionInterface;

class RevisionTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('get_revertable_revisions', [$this, 'getRevertableRevisions'])
		];
	}

	/**
	 * Get revertable revisions
	 *
	 * @param RevisionInterface $entity
	 *
	 * @return array
	 */
	public function getRevertableRevisions(RevisionInterface $entity): array
	{
		$entities = [];

		foreach ($entity->getRevisions() as $revision)
		{
			/**
			 * @var RevisionInterface|DeleteInterface $revision
			 */
			if ($revision === $entity->getRevision() || ($revision instanceof DeleteInterface && $revision->isDeleted()))
			{
				continue;
			}

			$entities[] = $revision;
		}

		return $entities;
	}
}