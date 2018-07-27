<?php

namespace Duo\PartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait EntityPartTrait
{
	/**
	 * @var Collection
	 */
	protected $parts;

	/**
	 * {@inheritdoc}
	 */
	public function addPart(PartInterface $part): EntityPartInterface
	{
		$this->getParts()->add($part);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removePart(PartInterface $part): EntityPartInterface
	{
		$this->getParts()->removeElement($part);

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