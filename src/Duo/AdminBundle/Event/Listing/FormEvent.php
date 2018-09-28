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
	 * @var object
	 */
	private $entity;

	/**
	 * FormEvent constructor
	 *
	 * @param FormInterface $form
	 * @param object $entity
	 */
	public function __construct(FormInterface $form, object $entity)
	{
		$this->form = $form;
		$this->entity = $entity;
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

	/**
	 * Set entity
	 *
	 * @param object $entity
	 *
	 * @return FormEvent
	 */
	public function setEntity(?object $entity): FormEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return object
	 */
	public function getEntity(): ?object
	{
		return $this->entity;
	}
}