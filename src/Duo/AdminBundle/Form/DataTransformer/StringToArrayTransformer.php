<?php

namespace Duo\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToArrayTransformer implements DataTransformerInterface
{
	/**
	 * @var string
	 */
	private $glue;

	/**
	 * StringToArrayTransformer constructor
	 *
	 * @param string $glue [optional]
	 */
	public function __construct(string $glue = PHP_EOL)
	{
		$this->glue = $glue;
	}

	/**
	 * {@inheritDoc}
	 */
	public function transform($value): ?array
	{
		if ($value === null)
		{
			return null;
		}

		return array_map('trim', explode($this->glue, $value));
	}

	/**
	 * {@inheritDoc}
	 */
	public function reverseTransform($values): ?string
	{
		if ($values === null || !count($values))
		{
			return null;
		}

		return implode($this->glue, $values);
	}
}
