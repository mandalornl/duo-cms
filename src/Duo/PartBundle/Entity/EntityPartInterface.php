<?php

namespace Duo\PartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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
	public function getParts();

	/**
	 * Get part reference class
	 *
	 * @return string
	 */
	public function getPartReferenceClass(): string;
}