<?php

namespace Duo\CoreBundle\Entity;

trait CloneTrait
{
	/**
	 * {@inheritdoc}
	 *
	 * @throws \Throwable
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

			call_user_func([$this, $reflectionMethod->getName()]);
		}
	}
}
