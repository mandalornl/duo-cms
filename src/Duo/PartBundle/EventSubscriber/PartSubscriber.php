<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\PartBundle\Entity\EntityPartInterface;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Repository\PartReferenceRepositoryInterface;

class PartSubscriber implements EventSubscriber
{
	/**
	 * @var PartInterface[]
	 */
	private $parts;

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
			Events::preRemove
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

		if (!$entity instanceof EntityPartInterface)
		{
			return;
		}

		/**
		 * @var PartReferenceRepositoryInterface $repo
		 */
		$repo = $args->getObjectManager()->getRepository($entity->getPartReferenceClass());

		$parts = $repo->getParts($entity);

		foreach ($parts as $part)
		{
			$entity->getParts()->add($part);
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

		if (!$entity instanceof EntityPartInterface || !count($entity->getParts()))
		{
			return;
		}

		/**
		 * @var ObjectManager $em
		 */
		$em = $args->getObjectManager();

		foreach ($entity->getParts() as $part)
		{
			$em->persist($part);
		}

		$em->flush();

		/**
		 * @var PartReferenceRepositoryInterface $repo
		 */
		$repo = $em->getRepository($entity->getPartReferenceClass());

		$repo->addPartReferences($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface)
		{
			return;
		}

		/**
		 * @var PartReferenceRepositoryInterface $repo
		 */
		$repo = $args->getObjectManager()->getRepository($entity->getPartReferenceClass());

		// store current part(s) for later reference
		$this->parts = $repo->getParts($entity);
	}

	/**
	 * On post update event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface)
		{
			return;
		}

		/**
		 * @var PartInterface[] $parts
		 */
		$parts = $entity->getParts()->toArray();

		/**
		 * @var ObjectManager $em
		 */
		$em = $args->getObjectManager();

		/**
		 * @var PartReferenceRepositoryInterface $repo
		 */
		$repo = $em->getRepository($entity->getPartReferenceClass());

		/**
		 * @var PartInterface[] $removableParts
		 */
		$removableParts = array_diff($this->parts, $parts);

		if (count($removableParts))
		{
			foreach ($removableParts as $part)
			{
				// remove part reference
				$repo->removePartReference($entity, $part);

				// remove part
				$em->remove($part);
			}

			$em->flush();
		}

		/**
		 * @var PartInterface[] $addableParts
		 */
		$addableParts = array_diff($parts, $this->parts);

		if (count($addableParts))
		{
			foreach ($addableParts as $part)
			{
				// add part
				$em->persist($part);

				// add part reference
				$repo->addPartReference($entity, $part);
			}

			$em->flush();
		}

		$this->parts = [];
	}

	/**
	 * On pre remove event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function preRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface || !count($entity->getParts()))
		{
			return;
		}

		/**
		 * @var ObjectManager $em
		 */
		$em = $args->getObjectManager();

		/**
		 * @var PartReferenceRepositoryInterface $repo
		 */
		$repo = $em->getRepository($entity->getPartReferenceClass());

		// remove part reference(s)
		$repo->removePartReferences($entity);

		// remove part(s)
		foreach ($entity->getParts() as $part)
		{
			$em->remove($part);
		}

		$em->flush();
	}
}