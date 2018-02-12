<?php

namespace Duo\SeoBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class MetaRobotsTransformer implements DataTransformerInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function transform($value): array
	{
		if ($value === null)
		{
			return [];
		}

		return explode(',', $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function reverseTransform($values): ?string
	{
		if ($values === null || !count($values))
		{
			return null;
		}

		return implode(',', $values);
	}
}
