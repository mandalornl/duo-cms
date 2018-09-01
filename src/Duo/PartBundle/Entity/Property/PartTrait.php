<?php

namespace Duo\PartBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;

trait PartTrait
{
	/**
	 * @var Collection
	 */
	protected $parts;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="part_version", type="string", length=16, nullable=true)
	 */
	protected $partVersion;

	/**
	 * {@inheritdoc}
	 */
	public function addPart(EntityPartInterface $part): PartInterface
	{
		$this->getParts()->add($part);

		// dirty entity
		$this->partVersion = uniqid();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removePart(EntityPartInterface $part): PartInterface
	{
		$this->getParts()->removeElement($part);

		// dirty entity
		$this->partVersion = uniqid();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParts(): Collection
	{
		return $this->parts = $this->parts ?: new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPartVersion(string $partVersion): PartInterface
	{
		$this->partVersion = $partVersion;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartVersion(): ?string
	{
		return $this->partVersion;
	}

	/**
	 * On clone parts
	 */
	protected function onCloneParts(): void
	{
		$parts = $this->getParts();
		$this->parts = new ArrayCollection();

		foreach ($parts as $part)
		{
			$this->parts[] = clone $part;
		}
	}
}