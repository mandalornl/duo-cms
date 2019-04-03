<?php

namespace Duo\SeoBundle\Controller\Listing;

use Duo\SeoBundle\Entity\Redirect;
use Duo\SeoBundle\Form\Listing\RedirectType;

trait RedirectConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return Redirect::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return RedirectType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'redirect';
	}
}
