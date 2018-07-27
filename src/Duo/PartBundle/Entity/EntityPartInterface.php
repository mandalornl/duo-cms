<?php

namespace Duo\PartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface EntityPartInterface
{
	/**
	 * Add part
	 *
	 * @param PartInterface $part
	 *
	 * @return EntityPartInterface
	 */
	public function addPart(PartInterface $part): EntityPartInterface;

	/**
	 * Remove part
	 *
	 * @param PartInterface $part
	 *
	 * @return EntityPartInterface
	 */
	public function removePart(PartInterface $part): EntityPartInterface;

	/**
	 * Get parts
	 *
	 * @return ArrayCollection
	 */
	public function getParts(): Collection;

	/**
	 * Get part reference class
	 *
	 * @return string
	 */
	public function getPartReferenceClass(): string;
}