<?php

namespace Duo\NodeBundle\Entity;

use Duo\BehaviorBundle\Entity\DuplicateInterface;
use Duo\BehaviorBundle\Entity\IdInterface;
use Duo\BehaviorBundle\Entity\TimestampInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;

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