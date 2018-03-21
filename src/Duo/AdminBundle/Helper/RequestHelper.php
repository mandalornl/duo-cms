<?php

namespace Duo\AdminBundle\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestHelper
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * RequestHelper constructor
	 *
	 * @param RequestStack $requestStack
	 */
	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}

	/**
	 * Get request
	 *
	 * @return Request
	 */
	public function getRequest(): Request
	{
		if ($this->requestStack === null || ($request = $this->requestStack->getCurrentRequest()) === null)
		{
			throw new \LogicException('A Request must be available.');
		}

		return $request;
	}
}