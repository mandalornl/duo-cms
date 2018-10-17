<?php

namespace Duo\CoreBundle\Twig;

use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface;

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
		return $entity->getRevisions()->filter(function(RevisionInterface $revision) use ($entity)
		{
			return !($revision === $entity->getRevision() ||
				($revision instanceof DeleteInterface && $revision->isDeleted()));
		})->toArray();
	}
}