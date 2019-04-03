<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Form\Listing\PageType;

trait PageConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return PageInterface::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return PageType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'page';
	}
}
