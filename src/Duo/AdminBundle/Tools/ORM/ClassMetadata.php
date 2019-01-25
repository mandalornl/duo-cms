<?php

namespace Duo\AdminBundle\Tools\ORM;

class ClassMetadata
{
	/**
	 * Get many to one target entity
	 *
	 * @param \ReflectionClass $reflectionClass
	 * @param string $suffix
	 *
	 * @return string
	 */
	public static function getManyToOneTargetEntity(\ReflectionClass $reflectionClass, string $suffix): ?string
	{
		$className = substr($reflectionClass->getName(), 0, -strlen($suffix));

		if (class_exists($className))
		{
			return $className;
		}

		$interfaceName = "{$className}Interface";

		if (interface_exists($interfaceName))
		{
			return $interfaceName;
		}

		return null;
	}

	/**
	 * Get one to many target entity
	 *
	 * @param \ReflectionClass $reflectionClass
	 * @param string $suffix
	 *
	 * @return string
	 */
	public static function getOneToManyTargetEntity(\ReflectionClass $reflectionClass, string $suffix): ?string
	{
		$className = sprintf('%s%s', $reflectionClass->getName(), $suffix);

		if (class_exists($className))
		{
			return $className;
		}

		if (($parentReflection = $reflectionClass->getParentClass()) !== false)
		{
			$className = sprintf(
				'%s\\%s%s',
				$parentReflection->getNamespaceName(),
				$reflectionClass->getShortName(),
				$suffix
			);

			if (class_exists($className))
			{
				return $className;
			}
		}

		return null;
	}

	/**
	 * ClassMetadata constructor
	 */
	private function __construct() {}
}
