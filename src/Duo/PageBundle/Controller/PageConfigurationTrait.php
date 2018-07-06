<?php

namespace Duo\PageBundle\Controller;

use Duo\PageBundle\Entity\Page;
use Duo\PageBundle\Form\PageType;

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