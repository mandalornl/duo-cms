<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

abstract class AbstractPagePart implements PagePartInterface, TimeStampInterface
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
	public function setValue(string $value = null): PagePartInterface
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
	public function setWeight(int $weight): PagePartInterface
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