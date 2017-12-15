<?php

namespace Softmedia\AdminBundle\Twig;

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
	 * @return string[]
	 */
	public function getCountries(): array
	{
		$countries = array_map(function(string $locale)
		{
			$country = strtoupper($locale === 'en' ? 'gb' : $locale);
			return Intl::getRegionBundle()->getCountryName($country, $locale);
		}, array_combine($this->locales, $this->locales));

		ksort($countries);

		return $countries;
	}

	/**
	 * Get locales
	 *
	 * @return string[]
	 */
	public function getLocales(): array
	{
		$locales = array_map(function(string $locale)
		{
			return ucfirst(Intl::getLocaleBundle()->getLocaleName($locale, $locale));
		}, array_combine($this->locales, $this->locales));

		ksort($locales);

		return $locales;
	}
}