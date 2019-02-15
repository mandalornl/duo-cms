<?php

namespace Duo\FormBundle\Event;

class FormSubmissionEvents
{
	/**
	 * Called on form submit
	 *
	 * @Event("Duo\FormBundle\Event\FormSubmissionEvent")
	 */
	const SUBMIT = 'duo_form.event.form_submission.submit';

	/**
	 * FormSubmissionEvents constructor
	 */
	private function __construct() {}
}
