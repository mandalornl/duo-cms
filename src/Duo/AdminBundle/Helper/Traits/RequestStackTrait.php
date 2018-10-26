<?php

namespace Duo\AdminBundle\Helper\Traits;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

trait RequestStackTrait
{
	/**
	 * @var RequestStack
	 */
	protected $requestStack;

	/**
	 * Set requestStack
	 *
	 * @param RequestStack $requestStack
	 *
	 * @required
	 */
	public function setRequestStack(RequestStack $requestStack): void
	{
		$this->requestStack = $requestStack;
	}

	/**
	 * Has request
	 *
	 * @return bool
	 */
	public function hasRequest(): bool
	{
		return $this->requestStack && $this->requestStack->getCurrentRequest();
	}

	/**
	 * Get request
	 *
	 * @return Request
	 */
	public function getRequest(): ?Request
	{
		if ($this->requestStack === null)
		{
			return null;
		}

		return $this->requestStack->getCurrentRequest();
	}
}