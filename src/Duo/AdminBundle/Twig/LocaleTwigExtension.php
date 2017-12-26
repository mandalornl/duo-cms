<?php

namespace Duo\AdminBundle\Twig;

use Symfony\Component\Intl\Intl;

class LocaleTwigExtension extends \Twig_Extension
{
	/**
	 * @var string[] $locales
	 */
	private $locales;

	/**
	 * LocaleTwigExtension constructor
	 *
	 * @param array $locales
	 */
	public function __construct(array $locales)
	{
		$this->locales = $locales;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('get_locales', [$this, 'getLocales']),
			new \Twig_SimpleFunction('get_countries', [$this, 'getCountries'])
		];
	}

	/**
	 * Get countries
	 *
	 * @param string $displayLocale
	 *
	 * @return string[]
	 */
	public function getCountries(string $displayLocale = null): array
	{
		$countries = array_map(function(string $locale) use ($displayLocale)
		{
			$country = strtoupper($locale === 'en' ? 'gb' : $locale);
			return Intl::getRegionBundle()->getCountryName($country, $displayLocale ?: $locale);
		}, array_combine($this->locales, $this->locales));

		ksort($countries);

		return $countries;
	}

	/**
	 * Get locales
	 *
	 * @param string $displayLocale
	 *
	 * @return string[]
	 */
	public function getLocales(string $displayLocale = null): array
	{
		$locales = array_map(function(string $locale) use ($displayLocale)
		{
			return ucfirst(Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale ?: $locale));
		}, array_combine($this->locales, $this->locales));

		ksort($locales);

		return $locales;
	}
}