<?php

namespace Duo\AdminBundle\Helper;

class SlugifyHelper
{
	/**
	 * SlugifyHelper constructor.
	 */
	private function __construct() {}

	/**
	 * Slugify
	 *
	 * @param string $input
	 * @param string $rule [optional]
	 * @param string $delimiter [optional]
	 *
	 * @return string
	 *
	 * @throws \IntlException
	 */
	public static function slugify(string $input, string $rule = null, string $delimiter = '-'): string
	{
		if (!$input)
		{
			return '';
		}

		$transliterator = \Transliterator::create(
			$rule ?: 'Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();'
		);

		if (!($slug = $transliterator->transliterate($input)))
		{
			throw new \IntlException(
				$transliterator->getErrorMessage(),
				$transliterator->getErrorCode()
			);
		}

		return trim(preg_replace("#[{$delimiter}\s]+#", $delimiter, $slug), $delimiter);
	}
}