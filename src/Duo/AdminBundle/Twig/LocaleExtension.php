<?php

namespace Duo\AdminBundle\Twig;

use Duo\AdminBundle\Helper\LocaleHelper;
use Symfony\Component\Intl\Intl;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocaleExtension extends AbstractExtension
{
	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * LocaleExtension constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('get_locales', [$this, 'getLocales']),
			new TwigFunction('get_countries', [$this, 'getCountries'])
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

		$list = array_map(function(string $locale) use ($displayLocale): ?string
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

		$list = array_map(function(string $locale) use ($displayLocale): ?string
		{
			return ucfirst(Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale ?: $locale));
		}, array_combine($locales, $locales));

		ksort($list);

		return $list;
	}
}
