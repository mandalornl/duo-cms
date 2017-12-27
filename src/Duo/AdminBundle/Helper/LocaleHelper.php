<?php

namespace Duo\AdminBundle\Helper;

use Symfony\Component\HttpFoundation\RequestStack;

class LocaleHelper
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * @var string
	 */
	private $defaultLocale = 'nl';

	/**
	 * Set requestStack
	 *
	 * @param RequestStack $requestStack
	 *
	 * @return LocaleHelper
	 */
	public function setRequestStack(RequestStack $requestStack): LocaleHelper
	{
		$this->requestStack = $requestStack;

		return $this;
	}

	/**
	 * Set defaultLocale
	 *
	 * @param string $defaultLocale
	 *
	 * @return LocaleHelper
	 */
	public function setDefaultLocale(string $defaultLocale): LocaleHelper
	{
		$this->defaultLocale = $defaultLocale;

		return $this;
	}

	/**
	 * Get defaultLocale
	 *
	 * @return string
	 */
	public function getDefaultLocale(): string
	{
		return $this->defaultLocale;
	}

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale(): string
	{
		if ($this->requestStack !== null && ($request = $this->requestStack->getCurrentRequest()) !== null)
		{
			return $request->getLocale();
		}

		return $this->defaultLocale;
	}
}