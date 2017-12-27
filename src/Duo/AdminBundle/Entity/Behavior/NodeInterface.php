<?php

namespace Duo\AdminBundle\Entity\Behavior;

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