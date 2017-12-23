<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface PublishInterface
{
	/**
	 * Set published
	 *
	 * @param boolean $published
	 *
	 * @return PublishInterface
	 */
	public function setPublished(bool $published = false): PublishInterface;

	/**
	 * Get published
	 *
	 * @return boolean
	 */
	public function getPublished(): bool;
}