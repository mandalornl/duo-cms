<?php

namespace Duo\PageBundle\Controller;

use Duo\PageBundle\Entity\Page;
use Duo\PageBundle\Form\PageListingType;

trait PageConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Page::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return PageListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'page';
	}
}