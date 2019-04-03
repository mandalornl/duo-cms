<?php

namespace Duo\MediaBundle\Controller\Listing;

use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Form\Listing\MediaType;

trait MediaConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return Media::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return MediaType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'media';
	}
}
