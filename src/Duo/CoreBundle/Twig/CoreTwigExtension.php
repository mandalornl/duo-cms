<?php

namespace Duo\CoreBundle\Twig;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\DraftInterface;
use Duo\CoreBundle\Entity\Property\PublishInterface;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Duo\CoreBundle\Entity\PreviewInterface;

class CoreTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function getTests(): array
	{
		return [
			new \Twig_SimpleTest('deletable', [$this, 'isDeletable']),
			new \Twig_SimpleTest('publishable', [$this, 'isPublishable']),
			new \Twig_SimpleTest('sortable', [$this, 'isSortable']),
			new \Twig_SimpleTest('translatable', [$this, 'isTranslatable']),
			new \Twig_SimpleTest('treeable', [$this, 'isTreeable']),
			new \Twig_SimpleTest('revisionable', [$this, 'isRevisionable']),
			new \Twig_SimpleTest('duplicatable', [$this, 'isDuplicatable']),
			new \Twig_SimpleTest('previewable', [$this, 'isPreviewable']),
			new \Twig_SimpleTest('draftable', [$this, 'isDraftable'])
		];
	}

	/**
	 * Is deletable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isDeletable(object $entity): bool
	{
		return $entity instanceof DeleteInterface;
	}

	/**
	 * Is publishable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isPublishable(object $entity): bool
	{
		if ($entity instanceof PublishInterface)
		{
			return true;
		}

		if ($entity instanceof TranslateInterface)
		{
			return $entity->getTranslations()->first() instanceof PublishInterface;
		}

		return false;
	}

	/**
	 * Is sortable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isSortable(object $entity): bool
	{
		return $entity instanceof SortInterface;
	}

	/**
	 * Is translatable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isTranslatable(object $entity): bool
	{
		return $entity instanceof TranslateInterface;
	}

	/**
	 * Is treeable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isTreeable(object $entity): bool
	{
		return $entity instanceof TreeInterface;
	}

	/**
	 * Is revisionable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isRevisionable(object $entity): bool
	{
		return $entity instanceof RevisionInterface;
	}

	/**
	 * Is duplicatable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isDuplicatable(object $entity): bool
	{
		return $entity instanceof DuplicateInterface;
	}

	/**
	 * Is previewable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isPreviewable(object $entity): bool
	{
		return $entity instanceof PreviewInterface;
	}

	/**
	 * Is draftable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isDraftable(object $entity): bool
	{
		return $entity instanceof DraftInterface;
	}
}
