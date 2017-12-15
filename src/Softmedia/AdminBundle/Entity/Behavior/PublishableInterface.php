<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

interface PublishableInterface
{
	/**
	 * Set published
	 *
	 * @param boolean $published
	 *
	 * @return PublishableInterface
	 */
	public function setPublished(bool $published = false): PublishableInterface;

	/**
	 * Get published
	 *
	 * @return boolean
	 */
	public function getPublished(): bool;
}