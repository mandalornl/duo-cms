<?php

namespace Duo\AdminBundle\Helper;

use Duo\AdminBundle\Helper\Traits\RequestStackTrait;

class LocaleHelper
{
	use RequestStackTrait;

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
		if (!$this->hasRequest())
		{
			return $this->defaultLocale;
		}

		return $this->getRequest()->getLocale();
	}

	/**
	 * Get preferred language
	 *
	 * @return string
	 */
	public function getPreferredLanguage(): string
	{
		if (!$this->hasRequest())
		{
			return $this->defaultLocale;
		}

		return $this->getRequest()->getPreferredLanguage($this->locales);
	}
}