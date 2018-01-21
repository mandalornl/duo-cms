<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

abstract class AbstractPartReference implements PartReferenceInterface, TimeStampInterface
{
	use IdTrait;
	use TimeStampTrait;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="entity_id", type="integer", nullable=true)
	 */
	protected $entityId;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="part_id", type="integer", nullable=true)
	 */
	protected $partId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="part_class", type="string", nullable=true)
	 */
	protected $partClass;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="integer", options={ "default" = 0 })
	 */
	protected $weight = 0;

	/**
	 * {@inheritdoc}
	 */
	public function setEntityId(int $entityId = null): PartReferenceInterface
	{
		$this->entityId = $entityId;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityId(): ?int
	{
		return $this->entityId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPartId(int $partId = null): PartReferenceInterface
	{
		$this->partId = $partId;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartId(): ?int
	{
		return $this->partId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPartClass(string $partClass = null): PartReferenceInterface
	{
		$this->partClass = $partClass;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartClass(): ?string
	{
		return $this->partClass;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setWeight(int $weight = 0): PartReferenceInterface
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getWeight(): int
	{
		return $this->weight;
	}
}