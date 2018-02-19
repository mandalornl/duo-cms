<?php

namespace Duo\FormBundle\Controller;

use Duo\FormBundle\Entity\FormResult;

trait FormResultConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return FormResult::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'form_result';
	}
}