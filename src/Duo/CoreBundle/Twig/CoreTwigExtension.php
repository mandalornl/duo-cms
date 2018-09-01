<?php

namespace Duo\CoreBundle\Twig;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\PublishInterface;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Duo\CoreBundle\Entity\ViewInterface;

class CoreTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
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
			new \Twig_SimpleTest('viewable', [$this, 'isViewable'])
		];
	}

	/**
	 * Is deletable
	 *
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isDeletable($entity): bool
	{
		return $entity instanceof DeleteInterface;
	}

	/**
	 * Is publishable
	 *
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isPublishable($entity): bool
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
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isSortable($entity): bool
	{
		return $entity instanceof SortInterface;
	}

	/**
	 * Is translatable
	 *
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isTranslatable($entity): bool
	{
		return $entity instanceof TranslateInterface;
	}

	/**
	 * Is treeable
	 *
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isTreeable($entity): bool
	{
		return $entity instanceof TreeInterface;
	}

	/**
	 * Is revisionable
	 *
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isRevisionable($entity): bool
	{
		return $entity instanceof RevisionInterface;
	}

	/**
	 * Is duplicatable
	 *
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isDuplicatable($entity): bool
	{
		return $entity instanceof DuplicateInterface;
	}

	/**
	 * Is viewable
	 *
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	public function isViewable($entity): bool
	{
		return $entity instanceof ViewInterface;
	}
}