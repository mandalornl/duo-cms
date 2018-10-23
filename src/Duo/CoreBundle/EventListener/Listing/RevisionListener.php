<?php

namespace Duo\CoreBundle\EventListener\Listing;

use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Entity\Property\UrlInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Duo\CoreBundle\Event\Listing\RevisionEvent;
use Duo\CoreBundle\Event\Listing\RevisionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RevisionListener implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			RevisionEvents::CLONE => 'onClone',
			RevisionEvents::REVERT => 'onRevert'
		];
	}

	/**
	 * On clone event
	 *
	 * @param RevisionEvent $event
	 */
	public function onClone(RevisionEvent $event): void
	{
		$this->setRevision($event);
		$this->setParentOnClone($event);
		$this->unsetUrlOnClone($event);
		$this->unsetTranslationUrlOnClone($event);
	}

	/**
	 * On revert event
	 *
	 * @param RevisionEvent $event
	 */
	public function onRevert(RevisionEvent $event): void
	{
		$this->setRevision($event);
		$this->setWeightOnRevert($event);
		$this->setParentOnRevert($event);
		$this->unsetUrlOnRevert($event);
		$this->unsetTranslationUrlOnRevert($event);
	}

	/**
	 * Set revision
	 *
	 * @param RevisionEvent $event
	 */
	private function setRevision(RevisionEvent $event): void
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		foreach ($origin->getRevisions() as $revision)
		{
			/**
			 * @var RevisionInterface $revision
			 */
			$revision->setRevision($entity);
		}
	}

	/**
	 * Set weight on revert
	 *
	 * @param RevisionEvent $event
	 */
	private function setWeightOnRevert(RevisionEvent $event): void
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		if (!$entity instanceof SortInterface || !$origin instanceof SortInterface)
		{
			return;
		}

		$entity->setWeight($origin->getWeight());
	}

	/**
	 * Set parent on clone
	 *
	 * @param RevisionEvent $event
	 */
	private function setParentOnClone(RevisionEvent $event): void
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
	private function setParentOnRevert(RevisionEvent $event): void
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		if (!$entity instanceof TreeInterface || !$origin instanceof TreeInterface)
		{
			return;
		}

		foreach ($origin->getChildren() as $child)
		{
			/**
			 * @var TreeInterface $child
			 */
			$child->setParent($entity);
		}
	}

	/**
	 * Unset url on clone
	 *
	 * @param RevisionEvent $event
	 */
	private function unsetUrlOnClone(RevisionEvent $event): void
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
	private function unsetUrlOnRevert(RevisionEvent $event): void
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
	private function unsetChildrenUrl(TreeInterface $entity): void
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
	private function unsetTranslationUrlOnClone(RevisionEvent $event): void
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
	private function unsetTranslationUrlOnRevert(RevisionEvent $event): void
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
	private function unsetTranslationChildrenUrl(TreeInterface $entity): void
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