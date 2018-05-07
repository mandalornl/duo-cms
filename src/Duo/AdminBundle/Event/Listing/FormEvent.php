<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

class FormEvent extends Event
{
	/**
	 * @var FormInterface
	 */
	private $form;

	/**
	 * FormEvent constructor
	 *
	 * @param FormInterface $form
	 */
	public function __construct(FormInterface $form)
	{
		$this->form = $form;
	}

	/**
	 * Set form
	 *
	 * @param FormInterface $form
	 *
	 * @return FormEvent
	 */
	public function setForm(FormInterface $form): FormEvent
	{
		$this->form = $form;

		return $this;
	}

	/**
	 * Get form
	 *
	 * @return FormInterface
	 */
	public function getForm(): FormInterface
	{
		return $this->form;
	}
}