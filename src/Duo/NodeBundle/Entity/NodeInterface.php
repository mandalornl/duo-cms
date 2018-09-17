<?php

namespace Duo\NodeBundle\Entity;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\VersionInterface;

interface NodeInterface extends IdInterface,
								DuplicateInterface,
								TimestampInterface,
								TranslateInterface,
								VersionInterface
{
	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return NodeInterface
	 */
	public function setName(?string $name): NodeInterface;

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string;
};