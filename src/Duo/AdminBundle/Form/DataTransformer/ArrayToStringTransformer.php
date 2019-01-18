<?php

namespace Duo\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ArrayToStringTransformer implements DataTransformerInterface
{
	/**
	 * @var string
	 */
	private $glue;

	/**
	 * ArrayToStringTransformer constructor
	 *
	 * @param string $glue [optional]
	 */
	public function __construct(string $glue = PHP_EOL)
	{
		$this->glue = $glue;
	}

	/**
	 * {@inheritdoc}
	 */
	public function transform($values): ?string
	{
		if ($values === null || !count($values))
		{
			return null;
		}

		return implode($this->glue, $values);
	}

	/**
	 * {@inheritdoc}
	 */
	public function reverseTransform($value): ?array
	{
		if ($value === null)
		{
			return null;
		}

		return array_map('trim', explode($this->glue, $value));
	}
}
