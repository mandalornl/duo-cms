<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\PartBundle\Entity\NodePartInterface;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Entity\PartReference;
use Duo\PartBundle\Repository\PartReferenceRepository;

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

		if (!$entity instanceof NodePartInterface)
		{
			return;
		}

		$parts = $args->getObjectManager()
			->getRepository(PartReference::class)
			->getParts($entity);

		foreach ($parts as $weight => $part)
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

		if (!$entity instanceof NodePartInterface || !count($entity->getParts()))
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

		$args->getObjectManager()
			->getRepository(PartReference::class)
			->addPartReferences($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePartInterface)
		{
			return;
		}

		// store current part(s) for later reference
		$this->parts = $args->getObjectManager()
			->getRepository(PartReference::class)
			->getParts($entity);
	}

	/**
	 * On post update event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePartInterface)
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
		 * @var PartReferenceRepository $repo
		 */
		$repo = $em->getRepository(PartReference::class);

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
	}

	/**
	 * On post remove event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof NodePartInterface || !count($entity->getParts()))
		{
			return;
		}

		// remove part reference(s)
		$args->getObjectManager()
			->getRepository(PartReference::class)
			->removePartReferences($entity);

		/**
		 * @var ObjectManager $em
		 */
		$em = $args->getObjectManager();

		// remove part(s)
		foreach ($entity->getParts() as $part)
		{
			$em->remove($part);
		}

		$em->flush();
	}
}