<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_part_reference",
 *     indexes={
 *		   @ORM\Index(name="entity_idx", columns={ "entity_id", "entity_class" }),
 *     	   @ORM\Index(name="part_idx", columns={ "part_id", "part_class" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\PartBundle\Repository\ReferenceRepository")
 */
class Reference implements ReferenceInterface
{
	use IdTrait;
	use TimestampTrait;

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
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="smallint", nullable=true)
	 */
	private $weight;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="section", type="string", nullable=true)
	 */
	private $section;

	/**
	 * {@inheritdoc}
	 */
	public function setEntityId(int $entityId = null): ReferenceInterface
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
	public function setEntityClass(string $entityClass = null): ReferenceInterface
	{
		$this->entityClass = $entityClass;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityClass(): ?string
	{
		return $this->entityClass;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPartId(int $partId = null): ReferenceInterface
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
	public function setPartClass(string $partClass = null): ReferenceInterface
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
	public function setWeight(int $weight = null): ReferenceInterface
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

	/**
	 * {@inheritdoc}
	 */
	public function setSection(string $section = null): ReferenceInterface
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
}