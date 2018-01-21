<?php

namespace Duo\PartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

interface NodePartInterface
{
	/**
	 * Add part
	 *
	 * @param PartInterface $part
	 *
	 * @return NodePartInterface
	 */
	public function addPart(PartInterface $part): NodePartInterface;

	/**
	 * Remove part
	 *
	 * @param PartInterface $part
	 *
	 * @return NodePartInterface
	 */
	public function removePart(PartInterface $part): NodePartInterface;

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