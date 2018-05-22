<?php

namespace Duo\PartBundle\Entity;

use Duo\CoreBundle\Entity\IdInterface;
use Duo\CoreBundle\Entity\TimestampInterface;

interface PartInterface extends IdInterface, TimestampInterface
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