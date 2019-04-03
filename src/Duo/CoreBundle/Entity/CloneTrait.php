<?php

namespace Duo\CoreBundle\Entity;

trait CloneTrait
{
	/**
	 * {@inheritDoc}
	 *
	 * @throws \ReflectionException
	 */
	public function __clone()
	{
		$reflectionClass = new \ReflectionClass($this);

		foreach ($reflectionClass->getMethods(
			\ReflectionMethod::IS_PROTECTED |
			\ReflectionMethod::IS_PRIVATE
		) as $reflectionMethod)
		{
			if (strpos($reflectionMethod->getName(), 'onClone') !== 0)
			{
				continue;
			}

			$reflectionMethod->setAccessible(true);
			$reflectionMethod->invoke($this);
		}
	}
}
