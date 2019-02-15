<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\CoreBundle\Entity\Property\PublishInterface;
use Symfony\Component\Security\Core\Security;

class PublishSubscriber implements EventSubscriber
{
	/**
	 * @var Security
	 */
	private $security;

	/**
	 * @var bool
	 */
	private $isPublished;

	/**
	 * PublishSubscriber constructor
	 *
	 * @param Security $security
	 */
	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::postLoad,
			Events::prePersist,
			Events::preUpdate
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

		if (!$entity instanceof PublishInterface)
		{
			return;
		}

		$this->isPublished = $entity->isPublished();
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PublishInterface ||
			!$entity->isPublished() ||
			($user = $this->security->getUser()) === null)
		{
			return;
		}

		$entity->setPublishedBy($user);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof PublishInterface ||
			($user = $this->security->getUser()) === null)
		{
			return;
		}

		if ($this->isPublished && !$entity->isPublished())
		{
			$entity->setUnpublishedBy($user);

			return;
		}

		$entity->setPublishedBy($user);
	}
}
