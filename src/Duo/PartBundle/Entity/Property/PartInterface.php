<?php

namespace Duo\PartBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\PartBundle\Entity\PartInterface as EntityPartInterface;

interface PartInterface
{
	/**
	 * Add part
	 *
	 * @param EntityPartInterface $part
	 *
	 * @return PartInterface
	 */
	public function addPart(EntityPartInterface $part): PartInterface;

	/**
	 * Remove part
	 *
	 * @param EntityPartInterface $part
	 *
	 * @return PartInterface
	 */
	public function removePart(EntityPartInterface $part): PartInterface;

	/**
	 * Get parts
	 *
	 * @return ArrayCollection
	 */
	public function getParts(): Collection;

	/**
	 * Get parts from section
	 *
	 * @param string $section
	 *
	 * @return ArrayCollection
	 */
	public function getPartsFromSection(string $section): Collection;

	/**
	 * Set partVersion
	 *
	 * @param string $partVersion
	 *
	 * @return PartInterface
	 */
	public function setPartVersion(string $partVersion): PartInterface;

	/**
	 * Get partVersion
	 *
	 * @return string
	 */
	public function getPartVersion(): ?string;
}