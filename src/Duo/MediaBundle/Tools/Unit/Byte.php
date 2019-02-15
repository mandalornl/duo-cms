<?php

namespace Duo\MediaBundle\Tools\Unit;

class Byte
{
	/**
	 * Convert bytes to human readable
	 *
	 * @param int $bytes
	 * @param bool $useKibibyte [optional]
	 *
	 * @return string
	 */
	public static function humanize(int $bytes, bool $useKibibyte = false): string
	{
		$base = $useKibibyte ? 1024 : 1000;
		$units = $useKibibyte ? ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'] : ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
		$i = (int)floor(log($bytes, $base));

		return @round($bytes / pow($base, $i), [0, 0, 2, 2, 3, 3][$i]) . ' ' . $units[$i];
	}

	/**
	 * Byte constructor
	 */
	private function __construct() {}
}
