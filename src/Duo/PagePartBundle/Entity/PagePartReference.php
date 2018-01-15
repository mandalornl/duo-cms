<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

/**
 * @ORM\Table(
 *     name="pp_reference",
 *     indexes={
 *		   @ORM\Index(name="entity_idx", columns={ "entity_id", "entity_fqcn" }),
 *		   @ORM\Index(name="page_part_idx", columns={ "page_part_id", "page_part_fqcn" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\PagePartBundle\Repository\PagePartReferenceRepository")
 */
class PagePartReference implements TimeStampInterface
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
	 * @ORM\Column(name="page_part_id", type="integer", nullable=true)
	 */
	private $pagePartId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="page_part_fqcn", type="string", nullable=true)
	 */
	private $pagePartFqcn;

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
	 * @return PagePartReference
	 */
	public function setEntityId(int $entityId = null): PagePartReference
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
	 * @return PagePartReference
	 */
	public function setEntityFqcn(string $entityFqcn = null): PagePartReference
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
	 * Set pagePartId
	 *
	 * @param int $pagePartId
	 *
	 * @return PagePartReference
	 */
	public function setPagePartId(int $pagePartId = null): PagePartReference
	{
		$this->pagePartId = $pagePartId;

		return $this;
	}

	/**
	 * Get pagePartId
	 *
	 * @return int
	 */
	public function getPagePartId(): ?int
	{
		return $this->pagePartId;
	}

	/**
	 * Set pagePartFqcn
	 *
	 * @param string $pagePartFqcn
	 *
	 * @return PagePartReference
	 */
	public function setPagePartFqcn(string $pagePartFqcn = null): PagePartReference
	{
		$this->pagePartFqcn = $pagePartFqcn;

		return $this;
	}

	/**
	 * Set pagePartFqcn
	 *
	 * @return string
	 */
	public function getPagePartFqcn(): ?string
	{
		return $this->pagePartFqcn;
	}

	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return PagePartReference
	 */
	public function setWeight(int $weight = 0): PagePartReference
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