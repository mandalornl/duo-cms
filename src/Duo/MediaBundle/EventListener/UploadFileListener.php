<?php

namespace Duo\MediaBundle\EventListener;

use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\MediaBundle\Entity\File;
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
	 */
	public function postCreate(FormEvent $event): void
	{
		$this->uploadFile($event);
	}

	/**
	 * On post update event
	 *
	 * @param FormEvent $event
	 */
	public function postUpdate(FormEvent $event): void
	{
		$this->uploadFile($event);
	}

	/**
	 * Upload file
	 *
	 * @param FormEvent $event
	 */
	private function uploadFile(FormEvent $event): void
	{
		$entity = $event->getEntity();

		if (!$entity instanceof File)
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