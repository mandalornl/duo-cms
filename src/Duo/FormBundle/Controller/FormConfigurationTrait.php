<?php

namespace Duo\FormBundle\Controller;

use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Form\FormListingType;

trait FormConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Form::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return FormListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'form';
	}
}