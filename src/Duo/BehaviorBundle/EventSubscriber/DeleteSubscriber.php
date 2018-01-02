<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\SecurityBundle\Helper\TokenHelper;

class DeleteSubscriber implements EventSubscriber
{
	/**
	 * @var TokenHelper
	 */
	private $tokenHelper;

	/**
	 * DeleteSubscriber constructor
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
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$this->setDeletedBy($args->getObject());
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setDeletedBy($args->getObject());
	}

	/**
	 * Set deleted by
	 *
	 * @param object $entity
	 */
	public function setDeletedBy($entity)
	{
		if (!$entity instanceof DeleteInterface || !$entity->isDeleted())
		{
			return;
		}

		if (($user = $this->tokenHelper->getUser()) === null)
		{
			return;
		}

		$entity->setDeletedBy($user);
	}
}