<?php

namespace Duo\FormBundle\Controller\Listing;

use Duo\FormBundle\Entity\FormSubmission;

trait FormSubmissionConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return FormSubmission::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'form_submission';
	}
}
