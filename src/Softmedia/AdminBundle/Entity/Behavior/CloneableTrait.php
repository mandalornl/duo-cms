<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

trait CloneableTrait
{
	/**
	 * {@inheritdoc}
	 */
	public function __clone()
	{
		$reflectionClass = new \ReflectionClass($this);

		foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PROTECTED) as $reflectionMethod)
		{
			if (strpos($reflectionMethod->getName(), 'onClone') !== 0)
			{
				continue;
			}

			call_user_func([$this, $reflectionMethod->getName()]);
		}
	}
}