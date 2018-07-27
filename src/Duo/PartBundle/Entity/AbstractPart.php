<?php

namespace Duo\PartBundle\Entity;

use Duo\CoreBundle\Entity\IdTrait;
use Duo\CoreBundle\Entity\TimestampTrait;

abstract class AbstractPart implements PartInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var int
	 */
	protected $weight = null;

	/**
	 * {@inheritdoc}
	 */
	public function setWeight(int $weight): PartInterface
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getWeight(): ?int
	{
		return $this->weight;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		$class = static::class;

		return "{$class}::{$this->id}";
	}
}