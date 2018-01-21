<?php

namespace Duo\PartBundle\Entity;

use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

abstract class AbstractPart implements PartInterface, TimeStampInterface
{
	use IdTrait;
	use TimeStampTrait;

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
		return "Part:{$this->id}";
	}
}