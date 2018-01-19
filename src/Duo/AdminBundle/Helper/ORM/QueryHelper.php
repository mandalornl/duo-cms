<?php

namespace Duo\AdminBundle\Helper\ORM;

class QueryHelper
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
	 * OrmHelper constructor
	 */
	private function __construct() {}
}