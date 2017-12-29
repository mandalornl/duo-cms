<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\SecurityBundle\Helper\UserHelper;

class PublishSubscriber implements EventSubscriber
{
	/**
	 * @var UserHelper
	 */
	private $helper;

	/**
	 * @var bool
	 */
	private $isPublished;

	/**
	 * PublishSubscriber constructor
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
	public function getSubscribedEvents()
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
	public function postLoad(LifecycleEventArgs $args)
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
	public function prePersist(LifecycleEventArgs $args)
	{
		$this->setBlame($args->getObject());
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setBlame($args->getObject());
	}

	/**
	 * Set blame
	 *
	 * @param object $entity
	 */
	private function setBlame($entity)
	{
		if (!$entity instanceof PublishInterface)
		{
			return;
		}

		if (($user = $this->helper->getUser()) === null)
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