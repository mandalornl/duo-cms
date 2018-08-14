<?php

namespace Duo\AdminBundle\Tools\ORM;

class Query
{
	/**
	 * Escape like
	 *
	 * @param string $value
	 * @param string $pattern [optional]
	 *
	 * @return string
	 */
	public static function escapeLike(string $value, string $pattern = '%%%s%%'): string
	{
		/**
		 * Sanitize value
		 *
		 * @param string $value
		 *
		 * @return string
		 */
		$sanitizeValue = function(string $value): string
		{
			$escapeChar = '!';
			$escape = [
				'\\' . $escapeChar, // must escape the escape-character for regex
				'\%',
				'\_'
			];
			$pattern = sprintf('#([%s])#', implode('', $escape));
			return preg_replace($pattern, $escapeChar . '$0', $value);
		};

		return sprintf($pattern, $sanitizeValue($value));
	}

	/**
	 * Query constructor
	 */
	private function __construct() {}
}