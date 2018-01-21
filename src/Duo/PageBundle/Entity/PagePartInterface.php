<?php

namespace Duo\PageBundle\Entity;

interface PagePartInterface
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
}