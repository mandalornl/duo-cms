<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\BehaviorBundle\Entity\SoftDeleteInterface;
use Duo\SecurityBundle\Helper\UserHelper;

class SoftDeleteSubscriber implements EventSubscriber
{
	/**
	 * @var UserHelper
	 */
	private $helper;

	/**
	 * SoftDeleteSubscriber constructor
	 *
	 * @param UserHelper $helper
	 */
	public function __construct(UserHelper $helper)
	{
		$this->helper = $helper;
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
		if (!$entity instanceof SoftDeleteInterface)
		{
			return;
		}

		if (($user = $this->helper->getUser()) === null)
		{
			return;
		}

		$entity->setDeletedBy($user);
	}
}