<?php

namespace Duo\MediaBundle\EventListener;

use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\AdminBundle\Event\Listing\FormEvents;
use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Helper\UploadHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UploadFileListener implements EventSubscriberInterface
{
	/**
	 * @var UploadHelper
	 */
	private $uploadHelper;

	/**
	 * FileListener constructor
	 *
	 * @param UploadHelper $uploadHelper
	 */
	public function __construct(UploadHelper $uploadHelper)
	{
		$this->uploadHelper = $uploadHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			FormEvents::POST_CREATE => 'postCreate',
			FormEvents::POST_UPDATE => 'postUpdate'
		];
	}

	/**
	 * On post create event
	 *
	 * @param FormEvent $event
	 *
	 * @throws \Throwable
	 */
	public function postCreate(FormEvent $event): void
	{
		$this->uploadFile($event);
	}

	/**
	 * On post update event
	 *
	 * @param FormEvent $event
	 *
	 * @throws \Throwable
	 */
	public function postUpdate(FormEvent $event): void
	{
		$this->uploadFile($event);
	}

	/**
	 * Upload file
	 *
	 * @param FormEvent $event
	 *
	 * @throws \Throwable
	 */
	private function uploadFile(FormEvent $event): void
	{
		$entity = $event->getEntity();

		if (!$entity instanceof Media)
		{
			return;
		}

		$form = $event->getForm();

		if (!$form->has('file'))
		{
			return;
		}

		$this->uploadHelper->upload($form->get('file')->getData(), $entity);
	}
}