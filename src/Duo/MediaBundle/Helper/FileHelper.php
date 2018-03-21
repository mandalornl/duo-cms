<?php

namespace Duo\MediaBundle\Helper;

class FileHelper
{
	/**
	 * Convert bytes to human readable
	 *
	 * @param int $bytes
	 * @param int $base [optional]
	 *
	 * @return string
	 */
	public static function humanizeBytes(int $bytes, int $base = 1024): string
	{
		$i = (int)floor(log($bytes, $base));

		return round($bytes / pow($base, $i), [0, 0, 2, 2, 3][$i]) . ['B', 'KB', 'MB', 'GB', 'TB'][$i];
	}

	/**
	 * FileHelper constructor
	 */
	private function __construct() {}
}