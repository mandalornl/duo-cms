<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

/**
 * @ORM\Table(
 *     name="part_reference",
 *     indexes={
 *		   @ORM\Index(name="entity_idx", columns={ "entity_id", "entity_fqcn" }),
 *		   @ORM\Index(name="part_idx", columns={ "part_id", "part_fqcn" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\PartBundle\Repository\PartReferenceRepository")
 */
class PartReference implements TimeStampInterface
{
	use IdTrait;
	use TimeStampTrait;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="entity_id", type="integer", nullable=true)
	 */
	private $entityId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="entity_fqcn", type="string", nullable=true)
	 */
	private $entityFqcn;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="part_id", type="integer", nullable=true)
	 */
	private $partId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="part_fqcn", type="string", nullable=true)
	 */
	private $partFqcn;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="integer", options={ "default" = 0 })
	 */
	private $weight = 0;

	/**
	 * Set entityId
	 *
	 * @param int $entityId
	 *
	 * @return PartReference
	 */
	public function setEntityId(int $entityId = null): PartReference
	{
		$this->entityId = $entityId;

		return $this;
	}

	/**
	 * Get entityId
	 *
	 * @return int
	 */
	public function getEntityId(): ?int
	{
		return $this->entityId;
	}

	/**
	 * Set entityFqcn
	 *
	 * @param string $entityFqcn
	 *
	 * @return PartReference
	 */
	public function setEntityFqcn(string $entityFqcn = null): PartReference
	{
		$this->entityFqcn = $entityFqcn;

		return $this;
	}

	/**
	 * Get entityFqcn
	 *
	 * @return string
	 */
	public function getEntityFqcn(): ?string
	{
		return $this->entityFqcn;
	}

	/**
	 * Set partId
	 *
	 * @param int $partId
	 *
	 * @return PartReference
	 */
	public function setPartId(int $partId = null): PartReference
	{
		$this->partId = $partId;

		return $this;
	}

	/**
	 * Get partId
	 *
	 * @return int
	 */
	public function getPartId(): ?int
	{
		return $this->partId;
	}

	/**
	 * Set partFqcn
	 *
	 * @param string $partFqcn
	 *
	 * @return PartReference
	 */
	public function setPartFqcn(string $partFqcn = null): PartReference
	{
		$this->partFqcn = $partFqcn;

		return $this;
	}

	/**
	 * Set partFqcn
	 *
	 * @return string
	 */
	public function getPartFqcn(): ?string
	{
		return $this->partFqcn;
	}

	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return PartReference
	 */
	public function setWeight(int $weight = 0): PartReference
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): int
	{
		return $this->weight;
	}
}