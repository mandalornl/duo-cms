<?php

namespace Duo\CoreBundle\Entity;

use Duo\CoreBundle\Entity\Property\DraftInterface as PropertyDraftInterface;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;

interface DraftInterface extends IdInterface, TimestampInterface
{
	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return DraftInterface
	 */
	public function setName(?string $name): DraftInterface;

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string;

	/**
	 * Set data
	 *
	 * @param array $data
	 *
	 * @return DraftInterface
	 */
	public function setData(?array $data): DraftInterface;

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function getData(): ?array;

	/**
	 * Set entity
	 *
	 * @param PropertyDraftInterface $entity
	 *
	 * @return DraftInterface
	 */
	public function setEntity(?PropertyDraftInterface $entity): DraftInterface;

	/**
	 * Get entity
	 *
	 * @return PropertyDraftInterface
	 */
	public function getEntity(): ?PropertyDraftInterface;
}
