<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;

abstract class AbstractPart implements PartInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var PropertyPartInterface
	 *
	 * Implement ORM mapping in child class
	 */
	protected $entity;

	/**
	 * @var Reference
	 *
	 * @ORM\OneToOne(targetEntity="Duo\PartBundle\Entity\Reference", cascade={ "persist" }, orphanRemoval=true)
	 * @ORM\JoinColumn(name="reference_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $reference;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="smallint", nullable=true)
	 */
	protected $weight;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="section", type="string", nullable=true)
	 */
	protected $section;

	/**
	 * {@inheritDoc}
	 */
	public function setEntity(?PropertyPartInterface $entity): PartInterface
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntity(): ?PropertyPartInterface
	{
		return $this->entity;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setReference(?ReferenceInterface $reference): PartInterface
	{
		$this->reference = $reference;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getReference(): ?ReferenceInterface
	{
		return $this->reference;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setWeight(?int $weight): PartInterface
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getWeight(): ?int
	{
		return $this->weight;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setSection(?string $section): PartInterface
	{
		$this->section = $section;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSection(): ?string
	{
		return $this->section;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString(): string
	{
		$className = static::class;

		return "{$className}::{$this->id}";
	}
}
