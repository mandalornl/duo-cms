<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormEvent extends AbstractEvent
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
	 * @param Request $request
	 */
	public function __construct(FormInterface $form, object $entity, Request $request)
	{
		$this->form = $form;
		$this->entity = $entity;

		parent::__construct($request);
	}

	/**
	 * Set form
	 *
	 * @param FormInterface $form
	 *
	 * @return FormEvent
	 */
	public function setForm(?FormInterface $form): FormEvent
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
