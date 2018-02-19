<?php

namespace Duo\SeoBundle\Controller;

use Duo\SeoBundle\Entity\Redirect;
use Duo\SeoBundle\Form\RedirectListingType;

trait RedirectConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Redirect::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return RedirectListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'redirect';
	}
}