<?php

namespace Duo\PartBundle\Entity;

use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\PartBundle\Entity\Property\PartInterface as PropertyPartInterface;

interface PartInterface extends IdInterface, TimestampInterface
{
	/**
	 * Set entity
	 *
	 * @param PropertyPartInterface $entity
	 *
	 * @return PartInterface
	 */
	public function setEntity(?PropertyPartInterface $entity): PartInterface;

	/**
	 * Get entity
	 *
	 * @return PropertyPartInterface
	 */
	public function getEntity(): ?PropertyPartInterface;

	/**
	 * Set reference
	 *
	 * @param ReferenceInterface $reference
	 *
	 * @return PartInterface
	 */
	public function setReference(?ReferenceInterface $reference): PartInterface;

	/**
	 * Get reference
	 *
	 * @return ReferenceInterface
	 */
	public function getReference(): ?ReferenceInterface;

	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return PartInterface
	 */
	public function setWeight(?int $weight): PartInterface;

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
	public function setSection(?string $section): PartInterface;

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