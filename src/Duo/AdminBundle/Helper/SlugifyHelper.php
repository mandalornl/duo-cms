<?php

namespace Duo\AdminBundle\Helper;

class SlugifyHelper
{
	/**
	 * SlugifyHelper constructor
	 */
	private function __construct() {}

	/**
	 * Slugify
	 *
	 * @param string $input
	 * @param string $delimiter [optional]
	 * @param string $rule [optional]
	 *
	 * @return string
	 *
	 * @throws \IntlException
	 */
	public static function slugify(string $input, string $delimiter = '-', string $rule = null): string
	{
		if (!$input)
		{
			return '';
		}

		$transliterator = \Transliterator::create(
			$rule ?: 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC; Lower();'
		);

		if (!($slug = $transliterator->transliterate($input)))
		{
			throw new \IntlException(
				$transliterator->getErrorMessage(),
				$transliterator->getErrorCode()
			);
		}

		$slug = preg_replace("#[\\{$delimiter}]+#", $delimiter, $slug);
		$slug = preg_replace("#[^0-9a-z\\{$delimiter}]+#", $delimiter, $slug);

		return trim($slug, $delimiter);
	}
}