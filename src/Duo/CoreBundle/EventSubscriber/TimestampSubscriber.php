<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Symfony\Component\Security\Core\Security;

class TimestampSubscriber implements EventSubscriber
{
	/**
	 * @var Security
	 */
	private $security;

	/**
	 * TimestampSubscriber constructor
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
			Events::prePersist,
			Events::preUpdate
		];
	}

	/**
	 * On pre persist event
	 *
	 * @param $args
	 *
	 * @throws \Exception
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof TimestampInterface)
		{
			return;
		}

		$user = $this->security->getUser();

		if ($entity->getCreatedAt() === null)
		{
			$entity->setCreatedAt(new \DateTime());

			if ($user !== null)
			{
				$entity->setCreatedBy($user);
			}
		}

		$entity->setModifiedAt(new \DateTime());

		if ($user !== null)
		{
			$entity->setModifiedBy($user);
		}
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 *
	 * @throws \Exception
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof TimestampInterface)
		{
			return;
		}

		$entity->setModifiedAt(new \DateTime());

		if (($user = $this->security->getUser()) !== null)
		{
			$entity->setModifiedBy($user);
		}
	}
}
