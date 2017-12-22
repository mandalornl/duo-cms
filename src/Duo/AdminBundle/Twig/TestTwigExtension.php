<?php

namespace Duo\AdminBundle\Twig;

class TestTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getTests()
	{
		return [
			new \Twig_SimpleTest('instanceof', [$this, 'isInstanceOf']),
			new \Twig_SimpleTest('bool', [$this, 'isBool']),
		];
	}

	/**
	 * Check whether or not object is instance of class
	 *
	 * @param object $object
	 * @param string $className
	 *
	 * @return bool
	 */
	public function isInstanceOf($object, string $className): bool
	{
		if (gettype($object) !== 'object')
		{
			return false;
		}

		return (new \ReflectionClass($className))->isInstance($object);
	}

	/**
	 * Check whether or not value is boolean
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isBool($value)
	{
		return in_array($value, [1, 0, true, false, '1', '0', 'true', 'false'], true);
	}
}