<?php

namespace Duo\PartBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\PartBundle\Collection\PartCollection;
use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;
use Duo\PartBundle\Entity\Reference;
use Duo\PartBundle\Entity\ReferenceInterface;
use Duo\PartBundle\Helper\PartHelper;

class PartSubscriber implements EventSubscriber
{
	/**
	 * @var PartHelper
	 */
	private $partHelper;

	/**
	 * @var EntityPartInterface[]
	 */
	private $parts;

	/**
	 * @var ReferenceInterface[]
	 */
	private $references;

	/**
	 * PartSubscriber constructor
	 *
	 * @param PartHelper $partHelper
	 */
	public function __construct(PartHelper $partHelper = null)
	{
		$this->partHelper = $partHelper;

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
			Events::postRemove
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

		if (!$entity instanceof PropertyPartInterface)
		{
			return;
		}

		/**
		 * @var EntityManagerInterface $manager
		 */
		$manager = $args->getObjectManager();

		$reflectionClass = $args->getObjectManager()->getClassMetadata(get_class($entity))->getReflectionClass();

		$property = $reflectionClass->getProperty('parts');
		$property->setAccessible(true);
		$property->setValue($entity, new PartCollection($manager, $entity));
	}

	/**
	 * On post persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface || !count($entity->getParts()))
		{
			return;
		}

		$manager = $args->getObjectManager();

		foreach ($entity->getParts() as $part)
		{
			$manager->persist($part);
		}

		$manager->flush();

		// add part references
		$this->partHelper->addReferences($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface)
		{
			return;
		}

		$oid = spl_object_hash($entity);

		// store current part(s) for later reference
		$this->parts[$oid] = $args->getObjectManager()->getRepository(Reference::class)->findParts($entity);
	}

	/**
	 * On post update event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface)
		{
			return;
		}

		$manager = $args->getObjectManager();

		$oid = spl_object_hash($entity);

		/**
		 * @var EntityPartInterface[] $parts
		 */
		$parts = $entity->getParts()->toArray();

		/**
		 * @var EntityPartInterface[] $removableParts
		 */
		$removableParts = array_diff($this->parts[$oid], $parts);

		if (count($removableParts))
		{
			foreach ($removableParts as $part)
			{
				// remove reference
				$this->partHelper->removeReference($entity, $part);

				// remove part
				$manager->remove($part);
			}

			$manager->flush();
		}

		/**
		 * @var EntityPartInterface[] $addableParts
		 */
		$addableParts = array_diff($parts, $this->parts[$oid]);

		if (count($addableParts))
		{
			foreach ($addableParts as $part)
			{
				// add part
				$manager->persist($part);
			}

			$manager->flush();

			// add references
			$this->partHelper->addReferences($entity, $addableParts);
		}

		unset($this->parts[$oid]);
	}

	/**
	 * On pre remove event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function preRemove(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface || !count($entity->getParts()))
		{
			return;
		}

		$oid = spl_object_hash($entity);

		$this->parts[$oid] = $entity->getParts()->toArray();
		$this->references[$oid] = $args->getObjectManager()
			->getRepository(Reference::class)
			->findReferences($entity);
	}

	/**
	 * On post remove event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postRemove(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PropertyPartInterface || !count($entity->getParts()))
		{
			return;
		}

		$manager = $args->getObjectManager();

		$oid = spl_object_hash($entity);

		foreach ($this->parts[$oid] as $part)
		{
			$manager->remove($part);
		}

		foreach ($this->references[$oid] as $reference)
		{
			$manager->remove($reference);
		}

		$manager->flush();

		unset($this->parts[$oid]);
		unset($this->references[$oid]);
	}
}