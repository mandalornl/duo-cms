<?php

namespace Duo\PagePartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\PagePartBundle\Entity\NodePagePartInterface;
use Duo\PagePartBundle\Entity\PagePartInterface;
use Duo\PagePartBundle\Entity\PagePartReference;
use Duo\PagePartBundle\Repository\PagePartReferenceRepository;

class PagePartSubscriber implements EventSubscriber
{
	/**
	 * @var PagePartInterface[]
	 */
	private $pageParts;

	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::postLoad,
			Events::postPersist,
			Events::preUpdate,
			Events::postUpdate,
			Events::postRemove
		];
	}

	/**
	 * On post load event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePagePartInterface)
		{
			return;
		}

		$pageParts = $args->getObjectManager()
			->getRepository(PagePartReference::class)
			->getPageParts($entity);

		foreach ($pageParts as $weight => $pagePart)
		{
			$pagePart->setWeight($weight);

			$entity->getPageParts()->set($weight, $pagePart);
		}
	}

	/**
	 * On post persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePagePartInterface || !count($entity->getPageParts()))
		{
			return;
		}

		/**
		 * @var ObjectManager $em
		 */
		$em = $args->getObjectManager();

		foreach ($entity->getPageParts() as $pagePart)
		{
			$em->persist($pagePart);
		}

		$em->flush();

		$args->getObjectManager()
			->getRepository(PagePartReference::class)
			->addPagePartReferences($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePagePartInterface)
		{
			return;
		}

		// store current page part(s) for later reference
		$this->pageParts = $args->getObjectManager()
			->getRepository(PagePartReference::class)
			->getPageParts($entity);
	}

	/**
	 * On post update event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePagePartInterface)
		{
			return;
		}

		/**
		 * @var PagePartInterface[] $pageParts
		 */
		$pageParts = $entity->getPageParts()->toArray();

		/**
		 * @var ObjectManager $em
		 */
		$em = $args->getObjectManager();

		/**
		 * @var PagePartReferenceRepository $repo
		 */
		$repo = $em->getRepository(PagePartReference::class);

		/**
		 * @var PagePartInterface[] $removablePageParts
		 */
		$removablePageParts = array_diff($this->pageParts, $pageParts);

		if (count($removablePageParts))
		{
			foreach ($removablePageParts as $pagePart)
			{
				// remove page part reference
				$repo->removePagePartReference($entity, $pagePart);

				// remove page part
				$em->remove($pagePart);
			}

			$em->flush();
		}

		/**
		 * @var PagePartInterface[] $addablePageParts
		 */
		$addablePageParts = array_diff($pageParts, $this->pageParts);

		if (count($addablePageParts))
		{
			foreach ($addablePageParts as $pagePart)
			{
				// add page part
				$em->persist($pagePart);

				// add page part reference
				$repo->addPagePartReference($entity, $pagePart);
			}

			$em->flush();
		}
	}

	/**
	 * On post remove event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePagePartInterface || !count($entity->getPageParts()))
		{
			return;
		}

		// remove page part reference(s)
		$args->getObjectManager()
			->getRepository(PagePartReference::class)
			->removePagePartReferences($entity);

		/**
		 * @var ObjectManager $em
		 */
		$em = $args->getObjectManager();

		// remove page part(s)
		foreach ($entity->getPageParts() as $pagePart)
		{
			$em->remove($pagePart);
		}

		$em->flush();
	}
}