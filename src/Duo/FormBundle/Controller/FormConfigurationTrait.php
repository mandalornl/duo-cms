<?php

namespace Duo\FormBundle\Controller;

use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Form\Listing\FormType;

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
		return FormType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'form';
	}
}