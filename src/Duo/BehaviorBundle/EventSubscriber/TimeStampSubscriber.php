<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\SecurityBundle\Helper\TokenHelper;

class TimeStampSubscriber implements EventSubscriber
{
	/**
	 * @var TokenHelper
	 */
	private $tokenHelper;

	/**
	 * TimeStampSubscriber constructor
	 *
	 * @param TokenHelper $tokenHelper
	 */
	public function __construct(TokenHelper $tokenHelper)
	{
		$this->tokenHelper = $tokenHelper;
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
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof TimeStampInterface)
		{
			return;
		}

		$user = $this->tokenHelper->getUser();

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
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof TimeStampInterface)
		{
			return;
		}

		$entity->setModifiedAt(new \DateTime());

		if (($user = $this->tokenHelper->getUser()) !== null)
		{
			$entity->setModifiedBy($user);
		}
	}
}