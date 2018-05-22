<?php

namespace Duo\CoreBundle\Twig;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Entity\DeleteInterface;
use Duo\CoreBundle\Entity\PublishInterface;
use Duo\CoreBundle\Entity\SortInterface;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Entity\RevisionInterface;
use Duo\PageBundle\Entity\ViewInterface;

class BehaviorTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getTests()
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
	 * @param object $entity
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
	 * @param object $entity
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
	 * @param object $entity
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
	 * @param object $entity
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
	 * @param object $entity
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
	 * @param object $entity
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
	 * @param object $entity
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
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isViewable($entity): bool
	{
		return $entity instanceof ViewInterface;
	}
}