<?php

namespace Duo\AdminBundle\EventListener\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\BlameInterface;
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
		$entity = $event->getEntity();
		$origin = $event->getOrigin();

		// update version
		foreach ($origin->getVersions() as $version)
		{
			/**
			 * @var VersionInterface $version
			 */
			$version->setVersion($entity);
		}

		// update parent for children
		if ($entity instanceof TreeInterface)
		{
			/**
			 * @var TreeInterface $child
			 */
			foreach ($entity->getChildren() as $child)
			{
				$child->setParent($entity);
			}

			// update url's for children
			if ($entity instanceof UrlInterface)
			{
				$this->updateChildrenUrls($entity->getChildren());
			}
		}

		// reset blameable
		if ($entity instanceof BlameInterface)
		{
			$entity
				->setCreatedBy(null)
				->setModifiedBy(null)
				->setDeletedBy(null);
		}

		// undelete entity
		if ($entity instanceof SoftDeleteInterface)
		{
			$entity->undelete();
		}
	}

	/**
	 * On revert event
	 *
	 * @param VersionEvent $event
	 */
	public function onRevert(VersionEvent $event)
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

		// update parent
		if ($origin instanceof TreeInterface)
		{
			/**
			 * @var TreeInterface $child
			 */
			foreach ($origin->getChildren() as $child)
			{
				$child->setParent($entity);
			}

			// update children url's
			if ($origin instanceof UrlInterface)
			{
				$this->updateChildrenUrls($origin->getChildren());
			}
		}

		// update weight
		if ($entity instanceof SortInterface)
		{
			/**
			 * @var SortInterface $origin
			 */
			$entity->setWeight($origin->getWeight());
		}
	}

	/**
	 * Update children url's
	 *
	 * @param iterable $children
	 */
	private function updateChildrenUrls(iterable $children)
	{
		/**
		 * @var TreeInterface|UrlInterface $child
		 */
		foreach ($children as $child)
		{
			$child->setUrl(null);

			$this->updateChildrenUrls($child->getChildren());
		}
	}
}