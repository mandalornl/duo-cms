<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_part_reference",
 *     indexes={
 *		   @ORM\Index(name="IDX_ENTITY", columns={ "entity_id", "entity_class" }),
 *     	   @ORM\Index(name="IDX_PART", columns={ "part_id", "part_class" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\PartBundle\Repository\ReferenceRepository")
 */
class Reference implements ReferenceInterface
{
	use IdTrait;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="entity_id", type="bigint", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $entityId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="entity_class", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $entityClass;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="part_id", type="bigint", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $partId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="part_class", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $partClass;


	/**
	 * {@inheritDoc}
	 */
	public function setEntityId(?int $entityId): ReferenceInterface
	{
		$this->entityId = $entityId;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityId(): ?int
	{
		return $this->entityId;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setEntityClass(?string $entityClass): ReferenceInterface
	{
		$this->entityClass = $entityClass;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityClass(): ?string
	{
		return $this->entityClass;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setPartId(?int $partId): ReferenceInterface
	{
		$this->partId = $partId;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPartId(): ?int
	{
		return $this->partId;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setPartClass(?string $partClass): ReferenceInterface
	{
		$this->partClass = $partClass;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPartClass(): ?string
	{
		return $this->partClass;
	}
}
