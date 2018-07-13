<?php

namespace Duo\SeoBundle\Controller;

use Duo\SeoBundle\Entity\Redirect;
use Duo\SeoBundle\Form\Listing\RedirectType;

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
		return RedirectType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'redirect';
	}
}