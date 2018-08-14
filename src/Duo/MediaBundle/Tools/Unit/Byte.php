<?php

namespace Duo\MediaBundle\Tools\Unit;

class Byte
{
	/**
	 * Convert bytes to human readable
	 *
	 * @param int $bytes
	 * @param int $base [optional]
	 *
	 * @return string
	 */
	public static function humanize(int $bytes, int $base = 1000): string
	{
		$i = (int)floor(log($bytes, $base));

		return round($bytes / pow($base, $i), [0, 0, 2, 2, 3][$i]) . ' ' . ['B', 'KB', 'MB', 'GB', 'TB'][$i];
	}

	/**
	 * Byte constructor
	 */
	private function __construct() {}
}