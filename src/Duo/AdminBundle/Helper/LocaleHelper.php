<?php

namespace Duo\AdminBundle\Helper;

use Symfony\Component\HttpFoundation\Request;
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
	 * @var string[]
	 */
	private $locales;

	/**
	 * LocaleHelper constructor
	 */
	public function __construct()
	{
		$this->locales = [ $this->defaultLocale ];
	}

	/**
	 * Set requestStack
	 *
	 * @param RequestStack $requestStack
	 *
	 * @return LocaleHelper
	 *
	 * @required
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
	 * Set locales
	 *
	 * @param array $locales
	 *
	 * @return LocaleHelper
	 */
	public function setLocales(array $locales): LocaleHelper
	{
		$this->locales = $locales;

		return $this;
	}

	/**
	 * Get locales
	 *
	 * @return array
	 */
	public function getLocales(): array
	{
		return $this->locales;
	}

	/**
	 * Set locales from string
	 *
	 * @param string $locales
	 * @param string $glue [optional]
	 *
	 * @return LocaleHelper
	 */
	public function setLocalesFromString(string $locales, string $glue = '|'): LocaleHelper
	{
		$this->locales = explode($glue, $locales);

		return $this;
	}

	/**
	 * Get locales as string
	 *
	 * @param string $glue [optional]
	 *
	 * @return string
	 */
	public function getLocalesAsString(string $glue = '|'): string
	{
		return implode($glue, $this->locales);
	}

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale(): string
	{
		if (($request = $this->getRequest()) === null)
		{
			return $this->defaultLocale;
		}

		return $request->getLocale();
	}

	/**
	 * Get preferred language
	 *
	 * @return string
	 */
	public function getPreferredLanguage(): string
	{
		if (($request = $this->getRequest()) === null)
		{
			return $this->defaultLocale;
		}

		return $request->getPreferredLanguage($this->locales);
	}

	/**
	 * Get request
	 *
	 * @return Request
	 */
	private function getRequest(): ?Request
	{
		if ($this->requestStack === null)
		{
			return null;
		}

		return $this->requestStack->getCurrentRequest();
	}
}