<?php

namespace Duo\FormBundle\Controller\Listing;

use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Form\Listing\FormType;

trait FormConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return Form::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return FormType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'form';
	}
}
