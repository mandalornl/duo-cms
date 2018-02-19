<?php

namespace Duo\MediaBundle\Controller;

use Duo\MediaBundle\Entity\Folder;
use Duo\MediaBundle\Form\FolderListingType;

trait FolderConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Folder::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return FolderListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'folder';
	}
}