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
		return sprintf($pattern, addcslashes($value, '%_'));
	}

	/**
	 * Query constructor
	 */
	private function __construct() {}
}
