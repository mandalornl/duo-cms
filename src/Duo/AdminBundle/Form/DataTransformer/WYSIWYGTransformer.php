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
		$values = [
			null,
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