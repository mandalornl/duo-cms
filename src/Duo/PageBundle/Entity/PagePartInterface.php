<?php

namespace Duo\PageBundle\Entity;

use Duo\PartBundle\Entity\PartInterface;

interface PagePartInterface extends PartInterface
{
	/**
	 * Set value
	 *
	 * @param string $value
	 *
	 * @return PagePartInterface
	 */
	public function setValue(string $value = null): PagePartInterface;

	/**
	 * Get value
	 *
	 * @return string
	 */
	public function getValue(): ?string;

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string;
}