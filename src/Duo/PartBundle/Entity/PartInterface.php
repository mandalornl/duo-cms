<?php

namespace Duo\PartBundle\Entity;

use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;

interface PartInterface extends IdInterface, TimestampInterface
{
	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return PartInterface
	 */
	public function setWeight(int $weight = null): PartInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): ?int;

	/**
	 * Set section
	 *
	 * @param string $section
	 *
	 * @return PartInterface
	 */
	public function setSection(string $section = null): PartInterface;

	/**
	 * Get section
	 *
	 * @return string
	 */
	public function getSection(): ?string;

	/**
	 * Get part form type
	 *
	 * @return string
	 */
	public function getPartFormType(): string;
}