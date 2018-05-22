<?php

namespace Duo\NodeBundle\Entity;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Entity\IdInterface;
use Duo\CoreBundle\Entity\TimestampInterface;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\CoreBundle\Entity\VersionInterface;

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
	public function setName(string $name = null): NodeInterface;

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string;
};