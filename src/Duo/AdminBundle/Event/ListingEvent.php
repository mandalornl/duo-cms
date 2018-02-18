<?php

namespace Duo\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

class ListingEvent extends Event
{
	/**
	 * @var object
	 */
	private $entity;

	/**
	 * @var FormInterface
	 */
	private $form;

	/**
	 * ListingEvent constructor
	 *
	 * @param object $entity
	 * @param FormInterface $form [optional]
	 */
	public function __construct($entity, FormInterface $form = null)
	{
		$this->entity = $entity;
		$this->form = $form;
	}

	/**
	 * Set entity
	 *
	 * @param object $entity
	 *
	 * @return ListingEvent
	 */
	public function setEntity($entity): ListingEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return object
	 */
	public function getEntity()
	{
		return $this->entity;
	}

	/**
	 * Set form
	 *
	 * @param FormInterface $form
	 *
	 * @return ListingEvent
	 */
	public function setForm(FormInterface $form): ListingEvent
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