<?php

namespace Duo\AdminBundle\Twig;

use Duo\AdminBundle\Helper\LocaleHelper;
use Symfony\Component\Intl\Intl;

class LocaleTwigExtension extends \Twig_Extension
{
	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * LocaleTwigExtension constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
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
		$locales = $this->localeHelper->getLocales();

		$list = array_map(function(string $locale) use ($displayLocale)
		{
			$country = strtoupper($locale === 'en' ? 'gb' : $locale);
			return Intl::getRegionBundle()->getCountryName($country, $displayLocale ?: $locale);
		}, array_combine($locales, $locales));

		ksort($list);

		return $list;
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
		$locales = $this->localeHelper->getLocales();

		$list = array_map(function(string $locale) use ($displayLocale)
		{
			return ucfirst(Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale ?: $locale));
		}, array_combine($locales, $locales));

		ksort($list);

		return $list;
	}
}