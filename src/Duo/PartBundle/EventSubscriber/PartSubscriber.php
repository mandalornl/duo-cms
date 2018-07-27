<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\PartBundle\Entity\EntityPartInterface;
use Duo\PartBundle\Entity\PartInterface;
use Duo\PartBundle\Entity\PartReferenceInterface;
use Duo\PartBundle\Repository\PartReferenceRepositoryInterface;

class PartSubscriber implements EventSubscriber
{
	/**
	 * @var PartInterface[]
	 */
	private $parts;

	/**
	 * @var PartReferenceInterface[]
	 */
	private $references;

	/**
	 * PartSubscriber constructor
	 */
	public function __construct()
	{
		$this->parts = [];
		$this->references = [];
	}

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
			Events::preRemove,
			Events::onFlush
		];
	}

	/**
	 * On post load event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface)
		{
			return;
		}

		/**
		 * @var PartReferenceRepositoryInterface $repository
		 */
		$repository = $args->getObjectManager()->getRepository($entity->getPartReferenceClass());

		foreach ($repository->getParts($entity) as $part)
		{
			$entity->getParts()->add($part);
		}
	}

	/**
	 * On post persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface || !count($entity->getParts()))
		{
			return;
		}

		/**
		 * @var ObjectManager $manager
		 */
		$manager = $args->getObjectManager();

		foreach ($entity->getParts() as $part)
		{
			$manager->persist($part);
		}

		$manager->flush();

		/**
		 * @var PartReferenceRepositoryInterface $repository
		 */
		$repository = $manager->getRepository($entity->getPartReferenceClass());

		$repository->addPartReferences($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface)
		{
			return;
		}

		$id = spl_object_hash($entity);

		/**
		 * @var PartReferenceRepositoryInterface $repository
		 */
		$repository = $args->getObjectManager()->getRepository($entity->getPartReferenceClass());

		// store current part(s) for later reference
		$this->parts[$id] = $repository->getParts($entity);
	}

	/**
	 * On post update event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface)
		{
			return;
		}

		$id = spl_object_hash($entity);

		/**
		 * @var PartInterface[] $parts
		 */
		$parts = $entity->getParts()->toArray();

		/**
		 * @var ObjectManager $manager
		 */
		$manager = $args->getObjectManager();

		/**
		 * @var PartReferenceRepositoryInterface $repository
		 */
		$repository = $manager->getRepository($entity->getPartReferenceClass());

		/**
		 * @var PartInterface[] $removableParts
		 */
		$removableParts = array_diff($this->parts[$id], $parts);

		if (count($removableParts))
		{
			foreach ($removableParts as $part)
			{
				// remove part reference
				$repository->removePartReference($entity, $part);

				// remove part
				$manager->remove($part);
			}

			$manager->flush();
		}

		/**
		 * @var PartInterface[] $addableParts
		 */
		$addableParts = array_diff($parts, $this->parts[$id]);

		if (count($addableParts))
		{
			foreach ($addableParts as $part)
			{
				// add part
				$manager->persist($part);

				// add part reference
				$repository->addPartReference($entity, $part);
			}

			$manager->flush();
		}

		unset($this->parts[$id]);
	}

	/**
	 * On pre remove event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function preRemove(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof EntityPartInterface || !count($entity->getParts()))
		{
			return;
		}

		$id = spl_object_hash($entity);

		/**
		 * @var PartReferenceRepositoryInterface $repository
		 */
		$repository = $args->getObjectManager()->getRepository($entity->getPartReferenceClass());

		$this->parts[$id] = $entity->getParts()->toArray();
		$this->references[$id] = $repository->getPartReferences($entity);
	}

	/**
	 * On flush event
	 *
	 * @param OnFlushEventArgs $args
	 */
	public function onFlush(OnFlushEventArgs $args): void
	{
		if (!count($this->parts) && !count($this->references))
		{
			return;
		}

		$unitOfWork = $args->getEntityManager()->getUnitOfWork();

		foreach ($this->parts as $id => $parts)
		{
			foreach ($parts as $part)
			{
				$unitOfWork->remove($part);
			}

			unset($this->parts[$id]);
		}

		foreach ($this->references as $id => $references)
		{
			foreach ($references as $reference)
			{
				$unitOfWork->remove($reference);
			}

			unset($this->references[$id]);
		}
	}
}