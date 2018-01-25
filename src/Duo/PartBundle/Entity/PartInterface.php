<?php

namespace Duo\PartBundle\Entity;

interface PartInterface
{
	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return PartInterface
	 */
	public function setWeight(int $weight): PartInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): ?int;

	/**
	 * Get part form type
	 *
	 * @return string
	 */
	public function getPartFormType(): string;
}