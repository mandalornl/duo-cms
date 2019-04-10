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
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class CoreExtension extends AbstractExtension
{
	/**
	 * {@inheritDoc}
	 */
	public function getTests(): array
	{
		return [
			new TwigTest('deletable', [$this, 'isDeletable']),
			new TwigTest('publishable', [$this, 'isPublishable']),
			new TwigTest('sortable', [$this, 'isSortable']),
			new TwigTest('translatable', [$this, 'isTranslatable']),
			new TwigTest('treeable', [$this, 'isTreeable']),
			new TwigTest('revisionable', [$this, 'isRevisionable']),
			new TwigTest('duplicatable', [$this, 'isDuplicatable']),
			new TwigTest('previewable', [$this, 'isPreviewable']),
			new TwigTest('draftable', [$this, 'isDraftable'])
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
