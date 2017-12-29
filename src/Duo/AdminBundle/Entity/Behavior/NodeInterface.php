<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Duo\BehaviorBundle\Entity\CloneInterface;
use Duo\BehaviorBundle\Entity\SoftDeleteInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;

interface NodeInterface extends SoftDeleteInterface,
								TranslateInterface,
								SortInterface,
								CloneInterface,
								VersionInterface,
								TimeStampInterface
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