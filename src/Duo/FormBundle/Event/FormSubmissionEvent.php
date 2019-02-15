<?php

namespace Duo\FormBundle\Event;

use Duo\FormBundle\Entity\FormSubmission;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

class FormSubmissionEvent extends Event
{
	/**
	 * @var FormSubmission
	 */
	private $submission;

	/**
	 * @var FormInterface
	 */
	private $form;

	/**
	 * FormSubmissionEvent constructor
	 *
	 * @param FormSubmission $submission
	 * @param FormInterface $form
	 */
	public function __construct(FormSubmission $submission, FormInterface $form)
	{
		$this->submission = $submission;
		$this->form = $form;
	}

	/**
	 * Set submission
	 *
	 * @param FormSubmission $submission
	 *
	 * @return FormSubmissionEvent
	 */
	public function setSubmission(?FormSubmission $submission): FormSubmissionEvent
	{
		$this->submission = $submission;

		return $this;
	}

	/**
	 * Get submission
	 *
	 * @return FormSubmission
	 */
	public function getSubmission(): ?FormSubmission
	{
		return $this->submission;
	}

	/**
	 * Set form
	 *
	 * @param FormInterface $form
	 *
	 * @return FormSubmissionEvent
	 */
	public function setForm(?FormInterface $form): FormSubmissionEvent
	{
		$this->form = $form;

		return $this;
	}

	/**
	 * Get form
	 *
	 * @return FormInterface
	 */
	public function getForm(): ?FormInterface
	{
		return $this->form;
	}
}
