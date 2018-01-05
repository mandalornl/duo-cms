<?php

namespace Duo\AdminBundle\Twig;

use Duo\AdminBundle\Entity\Behavior\ViewInterface;
use Duo\BehaviorBundle\Entity\CloneInterface;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;

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
			new \Twig_SimpleTest('versionable', [$this, 'isVersionable']),
			new \Twig_SimpleTest('cloneable', [$this, 'isCloneable']),
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
	 * Is versionable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isVersionable($entity): bool
	{
		return $entity instanceof VersionInterface;
	}

	/**
	 * Is cloneable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isCloneable($entity): bool
	{
		return $entity instanceof CloneInterface;
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