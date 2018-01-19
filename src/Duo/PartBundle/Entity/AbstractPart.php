<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

abstract class AbstractPart implements PartInterface, TimeStampInterface
{
	use IdTrait;
	use TimeStampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var int
	 */
	protected $weight = null;

	/**
	 * {@inheritdoc}
	 */
	public function setValue(string $value = null): PartInterface
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValue(): ?string
	{
		return $this->value;
	}

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
		return $this->value;
	}
}