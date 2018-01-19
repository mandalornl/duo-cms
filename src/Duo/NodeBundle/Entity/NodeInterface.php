<?php

namespace Duo\NodeBundle\Entity;

use Duo\BehaviorBundle\Entity\DuplicateInterface;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;

interface NodeInterface extends DuplicateInterface,
								TimeStampInterface,
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