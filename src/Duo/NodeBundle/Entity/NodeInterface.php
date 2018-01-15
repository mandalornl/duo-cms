<?php

namespace Duo\NodeBundle\Entity;

use Duo\BehaviorBundle\Entity\CloneInterface;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\RevisionInterface;

interface NodeInterface extends DeleteInterface,
								TranslateInterface,
								SortInterface,
								CloneInterface,
								RevisionInterface,
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