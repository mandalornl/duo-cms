<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Form\Listing\PageType;

trait PageConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return PageInterface::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return PageType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'page';
	}
}