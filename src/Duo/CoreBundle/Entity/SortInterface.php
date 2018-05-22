<?php

namespace Duo\CoreBundle\Entity;

interface SortInterface
{
	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return SortInterface
	 */
	public function setWeight(int $weight = null): SortInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): ?int;
}