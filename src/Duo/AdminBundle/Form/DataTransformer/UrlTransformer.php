<?php

namespace Duo\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class UrlTransformer implements DataTransformerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function transform($value)
	{
		return "/{$value}";
	}

	/**
	 * {@inheritDoc}
	 */
	public function reverseTransform($value)
	{
		return ltrim($value, '/');
	}
}
