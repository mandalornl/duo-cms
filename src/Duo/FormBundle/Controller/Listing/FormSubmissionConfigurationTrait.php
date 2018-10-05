<?php

namespace Duo\FormBundle\Controller\Listing;

use Duo\FormBundle\Entity\FormSubmission;

trait FormSubmissionConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return FormSubmission::class;
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
		return 'form_submission';
	}
}