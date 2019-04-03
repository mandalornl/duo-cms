<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEvent extends Event implements EventInterface
{
	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * AbstractEvent constructor
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setRequest(?Request $request): EventInterface
	{
		$this->request = $request;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRequest(): ?Request
	{
		return $this->request;
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasResponse(): bool
	{
		return $this->response instanceof Response;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getResponse(): ?Response
	{
		return $this->response;
	}
}
