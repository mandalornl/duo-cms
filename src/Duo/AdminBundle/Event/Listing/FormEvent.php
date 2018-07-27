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
	 * @var mixed
	 */
	private $entity;

	/**
	 * FormEvent constructor
	 *
	 * @param FormInterface $form
	 * @param mixed $entity
	 */
	public function __construct(FormInterface $form, $entity)
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
	 * @param mixed $entity
	 *
	 * @return FormEvent
	 */
	public function setEntity($entity): FormEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return mixed
	 */
	public function getEntity()
	{
		return $this->entity;
	}
}