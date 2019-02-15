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
	 * {@inheritdoc}
	 */
	public function setRequest(?Request $request): EventInterface
	{
		$this->request = $request;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRequest(): ?Request
	{
		return $this->request;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasResponse(): bool
	{
		return $this->response instanceof Response;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getResponse(): ?Response
	{
		return $this->response;
	}
}
