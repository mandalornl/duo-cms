<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
	 * @var Request
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

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
		$this->request = $request;
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

	/**
	 * Set request
	 *
	 * @param Request $request
	 *
	 * @return FormEvent
	 */
	public function setRequest(?Request $request): FormEvent
	{
		$this->request = $request;

		return $this;
	}

	/**
	 * Get request
	 *
	 * @return Request
	 */
	public function getRequest(): ?Request
	{
		return $this->request;
	}

	/**
	 * Has response
	 *
	 * @return bool
	 */
	public function hasResponse(): bool
	{
		return $this->response instanceof Response;
	}

	/**
	 * Get response
	 *
	 * @return Response
	 */
	public function getResponse(): ?Response
	{
		return $this->response;
	}
}