<?php

namespace Duo\AdminBundle\Helper;

class ReflectionClassHelper
{
	/**
	 * ReflectionClassHelper constructor
	 */
	private function __construct() {}

	/**
	 * Check whether or not class has trait
	 *
	 * @param \ReflectionClass $reflectionClass
	 * @param string $traitName
	 * @param bool $recursive [optional]
	 *
	 * @return bool
	 */
	public static function hasTrait(\ReflectionClass $reflectionClass, string $traitName, bool $recursive = true): bool
	{
		if (in_array($traitName, $reflectionClass->getTraitNames()))
		{
			return true;
		}

		if ($recursive && ($parentClass = $reflectionClass->getParentClass()))
		{
			return self::hasTrait($parentClass, $traitName, $recursive);
		}

		return false;
	}
}