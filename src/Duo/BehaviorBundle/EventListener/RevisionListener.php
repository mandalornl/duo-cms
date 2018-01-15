<?php

namespace Duo\BehaviorBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\UrlInterface;
use Duo\BehaviorBundle\Entity\RevisionInterface;
use Duo\BehaviorBundle\Event\RevisionEvent;

class RevisionListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * RevisionableListener constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * On clone event
	 *
	 * @param RevisionEvent $event
	 */
	public function onClone(RevisionEvent $event)
	{
		$this->setRevision($event);
		$this->unsetBlame($event);
		$this->undelete($event);
		$this->setParentOnClone($event);
		$this->unsetUrlOnClone($event);
		$this->unsetTranslationUrlOnClone($event);
	}

	/**
	 * On revert event
	 *
	 * @param RevisionEvent $event
	 */
	public function onRevert(RevisionEvent $event)
	{
		$this->setRevision($event);
		$this->setWeight($event);
		$this->setParentOnRevert($event);
		$this->unsetUrlOnRevert($event);
		$this->unsetTranslationUrlOnRevert($event);
	}

	/**
	 * Set revision
	 *
	 * @param RevisionEvent $event
	 */
	private function setRevision(RevisionEvent $event)
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		/**
		 * @var RevisionInterface $revision
		 */
		foreach ($origin->getRevisions() as $revision)
		{
			$revision->setRevision($entity);
		}
	}

	/**
	 * Unset blame
	 *
	 * @param RevisionEvent $event
	 */
	private function unsetBlame(RevisionEvent $event)
	{
		$entity = $event->getEntity();

		if ($entity instanceof TimeStampInterface)
		{
			$entity
				->setCreatedBy(null)
				->setModifiedBy(null);
		}

		if ($entity instanceof DeleteInterface)
		{
			$entity->setDeletedBy(null);
		}

		if ($entity instanceof PublishInterface)
		{
			$entity
				->setPublishedBy(null)
				->setUnpublishedBy(null);
		}
	}

	/**
	 * Undelete entity
	 *
	 * @param RevisionEvent $event
	 */
	private function undelete(RevisionEvent $event)
	{
		$entity = $event->getEntity();

		if (!$entity instanceof DeleteInterface)
		{
			return;
		}

		$entity->undelete();
	}

	/**
	 * Set weight
	 *
	 * @param RevisionEvent $event
	 */
	private function setWeight(RevisionEvent $event)
	{
		$entity = $event->getEntity();

		if (!$entity instanceof SortInterface)
		{
			return;
		}

		/**
		 * @var SortInterface $origin
		 */
		$origin = $event->getOrigin();

		$entity->setWeight($origin->getWeight());
	}

	/**
	 * Set parent on clone
	 *
	 * @param RevisionEvent $event
	 */
	private function setParentOnClone(RevisionEvent $event)
	{
		$entity = $event->getEntity();

		if (!$entity instanceof TreeInterface)
		{
			return;
		}

		foreach ($entity->getChildren() as $child)
		{
			/**
			 * @var TreeInterface $child
			 */
			$child->setParent($entity);
		}
	}

	/**
	 * Set parent on revert
	 *
	 * @param RevisionEvent $event
	 */
	private function setParentOnRevert(RevisionEvent $event)
	{
		$origin = $event->getOrigin();

		if (!$origin instanceof TreeInterface)
		{
			return;
		}

		foreach ($origin->getChildren() as $child)
		{
			/**
			 * @var TreeInterface $child
			 */
			$child->setParent($event->getEntity());
		}
	}

	/**
	 * Unset url on clone
	 *
	 * @param RevisionEvent $event
	 */
	private function unsetUrlOnClone(RevisionEvent $event)
	{
		$entity = $event->getEntity();

		if (!$entity instanceof TreeInterface)
		{
			return;
		}

		$this->unsetChildrenUrl($entity);
	}

	/**
	 * Unset url on revert
	 *
	 * @param RevisionEvent $event
	 */
	private function unsetUrlOnRevert(RevisionEvent $event)
	{
		$origin = $event->getOrigin();

		if (!$origin instanceof TreeInterface)
		{
			return;
		}

		$this->unsetChildrenUrl($origin);
	}

	/**
	 * Unset children url
	 *
	 * @param TreeInterface $entity
	 */
	private function unsetChildrenUrl(TreeInterface $entity)
	{
		if (!$entity instanceof UrlInterface)
		{
			return;
		}

		foreach ($entity->getChildren() as $child)
		{
			/**
			 * @var TreeInterface|UrlInterface $child
			 */
			$child->setUrl(null);

			$this->unsetChildrenUrl($child);
		}
	}

	/**
	 * Unset translation url on clone
	 *
	 * @param RevisionEvent $event
	 */
	private function unsetTranslationUrlOnClone(RevisionEvent $event)
	{
		$entity = $event->getEntity();

		if (!$entity instanceof TranslateInterface || !$entity instanceof TreeInterface)
		{
			return;
		}

		$translation = $entity->getTranslations()->first();

		if (!$translation instanceof UrlInterface)
		{
			return;
		}

		$this->unsetTranslationChildrenUrl($entity);
	}

	/**
	 * Unset translation url on revert
	 *
	 * @param RevisionEvent $event
	 */
	private function unsetTranslationUrlOnRevert(RevisionEvent $event)
	{
		$origin = $event->getOrigin();

		if (!$origin instanceof TranslateInterface || !$origin instanceof TreeInterface)
		{
			return;
		}

		$translation = $origin->getTranslations()->first();

		if (!$translation instanceof UrlInterface)
		{
			return;
		}

		$this->unsetTranslationChildrenUrl($origin);
	}

	/**
	 * Unset translation children url
	 *
	 * @param TreeInterface $entity
	 */
	private function unsetTranslationChildrenUrl(TreeInterface $entity)
	{
		foreach ($entity->getChildren() as $child)
		{
			/**
			 * @var TreeInterface|TranslateInterface $child
			 */
			foreach ($child->getTranslations() as $translation)
			{
				/**
				 * @var UrlInterface $translation
				 */
				$translation->setUrl(null);
			}

			$this->unsetTranslationChildrenUrl($child);
		}
	}
}