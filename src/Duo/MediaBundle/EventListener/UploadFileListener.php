<?php

namespace Duo\MediaBundle\EventListener;

use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Helper\UploadHelper;

class UploadFileListener
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
