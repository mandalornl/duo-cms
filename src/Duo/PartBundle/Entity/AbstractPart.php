<?php

namespace Duo\PartBundle\Entity;

use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;

abstract class AbstractPart implements PartInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var int
	 */
	protected $weight;

	/**
	 * @var string
	 */
	protected $section;

	/**
	 * {@inheritdoc}
	 */
	public function setWeight(int $weight = null): PartInterface
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
	public function setSection(string $section = null): PartInterface
	{
		$this->section = $section;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSection(): ?string
	{
		return $this->section;
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