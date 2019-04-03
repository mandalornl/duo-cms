<?php

namespace Duo\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class WYSIWYGTransformer implements DataTransformerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function transform($value)
	{
		return $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function reverseTransform($value)
	{
		$values = [
			'',
			' ',
			'<p>&nbsp;</p>',
			'<p> </p>'
		];

		if (in_array($value, $values))
		{
			return null;
		}

		return $value;
	}
}
