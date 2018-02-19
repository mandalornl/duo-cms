<?php

namespace Duo\MediaBundle\Controller;

use Duo\MediaBundle\Entity\File;
use Duo\MediaBundle\Form\FileListingType;

trait FileConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return File::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return FileListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'file';
	}
}