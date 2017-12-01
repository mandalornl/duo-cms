<?php

namespace Softmedia\AdminBundle\Twig;

class TwigTestExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getTests()
	{
		return [
			new \Twig_SimpleTest('instanceof', [$this, 'isInstanceOf'])
		];
	}

	/**
	 * Check whether object is instance of class
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
}