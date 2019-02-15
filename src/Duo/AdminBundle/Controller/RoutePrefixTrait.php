<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Routing\Annotation\Route;

trait RoutePrefixTrait
{
	/**
	 * @var string
	 */
	protected $routePrefix;

	/**
	 * Get route prefix
	 *
	 * @return string
	 *
	 * @throws \Throwable
	 */
	protected function getRoutePrefix(): string
	{
		if ($this->routePrefix !== null)
		{
			return $this->routePrefix;
		}

		$annotationName = Route::class;
		$reflectionClass = new \ReflectionClass($this);

		$reader = new AnnotationReader();
		$annotation = $reader->getClassAnnotation($reflectionClass, $annotationName);

		if ($annotation === null)
		{
			throw new AnnotationException("Controller '{$reflectionClass->getName()}' is missing annotation '{$annotationName}'");
		}

		return $this->routePrefix = rtrim($annotation->getName(), '_');
	}
}
