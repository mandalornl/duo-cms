<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

interface SortableInterface
{
	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return SortableInterface
	 */
	public function setWeight(int $weight = null): SortableInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): ?int;
}