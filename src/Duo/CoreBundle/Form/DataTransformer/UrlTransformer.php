<?php

namespace Duo\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class UrlTransformer implements DataTransformerInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function transform($value)
	{
		return "/{$value}";
	}

	/**
	 * {@inheritdoc}
	 */
	public function reverseTransform($value)
	{
		return rtrim($value, '/');
	}
}