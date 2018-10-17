<?php

namespace Duo\CoreBundle\Entity\Property;

interface UuidInterface
{
	/**
	 * Get uuid
	 *
	 * @return string
	 */
	public function getUuid(): ?string;
}