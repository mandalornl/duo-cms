<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface NodeInterface extends BlameableInterface,
								SoftDeletableInterface,
								TranslatableInterface,
								SortableInterface,
								CloneableInterface,
								VersionableInterface,
								TimeStampableInterface
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