<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeleteInterface;
use Duo\AdminBundle\Entity\Behavior\SortInterface;
use Duo\AdminBundle\Entity\Behavior\TranslateInterface;
use Duo\AdminBundle\Entity\Behavior\TreeInterface;
use Duo\AdminBundle\Entity\Behavior\UrlInterface;
use Duo\AdminBundle\Entity\Behavior\VersionInterface;
use Duo\AdminBundle\Event\Behavior\VersionEvent;

class VersionListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * VersionableListener constructor
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
	 * @param VersionEvent $event
	 */
	public function onClone(VersionEvent $event)
	{
		$this->setVersion($event);
		// TODO: implement !
		//$this->unsetBlame($event);
		$this->undelete($event);
		$this->setParentOnClone($event);
		$this->unsetUrlOnClone($event);
		$this->unsetTranslationUrlOnClone($event);
	}

	/**
	 * On revert event
	 *
	 * @param VersionEvent $event
	 */
	public function onRevert(VersionEvent $event)
	{
		$this->setVersion($event);
		$this->setWeight($event);
		$this->setParentOnRevert($event);
		$this->unsetUrlOnRevert($event);
		$this->unsetTranslationUrlOnRevert($event);
	}

	/**
	 * Set version
	 *
	 * @param VersionEvent $event
	 */
	private function setVersion(VersionEvent $event)
	{
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		/**
		 * @var VersionInterface $version
		 */
		foreach ($origin->getVersions() as $version)
		{
			$version->setVersion($entity);
		}
	}

	/**
	 * Unset blame
	 *
	 * @param VersionEvent $event
	 */
	private function unsetBlame(VersionEvent $event)
	{
//		$entity = $event->getEntity();
//
//		if (!$entity instanceof BlameInterface)
//		{
//			return;
//		}
//
//		$entity
//			->setCreatedBy(null)
//			->setModifiedBy(null)
//			->setDeletedBy(null);
	}

	/**
	 * Undelete entity
	 *
	 * @param VersionEvent $event
	 */
	private function undelete(VersionEvent $event)
	{
		$entity = $event->getEntity();

		if (!$entity instanceof SoftDeleteInterface)
		{
			return;
		}

		$entity->undelete();
	}

	/**
	 * Set weight
	 *
	 * @param VersionEvent $event
	 */
	private function setWeight(VersionEvent $event)
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
	 * @param VersionEvent $event
	 */
	private function setParentOnClone(VersionEvent $event)
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
	 * @param VersionEvent $event
	 */
	private function setParentOnRevert(VersionEvent $event)
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
	 * @param VersionEvent $event
	 */
	private function unsetUrlOnClone(VersionEvent $event)
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
	 * @param VersionEvent $event
	 */
	private function unsetUrlOnRevert(VersionEvent $event)
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
	 * @param VersionEvent $event
	 */
	private function unsetTranslationUrlOnClone(VersionEvent $event)
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
	 * @param VersionEvent $event
	 */
	private function unsetTranslationUrlOnRevert(VersionEvent $event)
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