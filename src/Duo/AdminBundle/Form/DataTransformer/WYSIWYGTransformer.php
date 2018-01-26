<?php

namespace Duo\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class WYSIWYGTransformer implements DataTransformerInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function transform($value)
	{
		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function reverseTransform($value)
	{
		if ($value === null || $value === '<p>&nbsp;</p>')
		{
			return null;
		}

		return $value;
	}
}