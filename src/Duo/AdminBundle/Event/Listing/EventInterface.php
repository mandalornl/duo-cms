<?php

namespace Duo\AdminBundle\Event\Listing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EventInterface
{
	/**
	 * Set request
	 *
	 * @param Request $request
	 *
	 * @return EventInterface
	 */
	public function setRequest(?Request $request): EventInterface;

	/**
	 * Get request
	 *
	 * @return Request
	 */
	public function getRequest(): ?Request;

	/**
	 * Has response
	 *
	 * @return bool
	 */
	public function hasResponse(): bool;

	/**
	 * Get response
	 *
	 * @return Response
	 */
	public function getResponse(): ?Response;
}
