<?php

namespace Duo\MediaBundle\Controller;

use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Form\Listing\MediaType;

trait MediaConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Media::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return MediaType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'media';
	}
}