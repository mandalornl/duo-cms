<?php

namespace Duo\CoreBundle\Entity\Property;

interface UrlInterface
{
	/**
	 * Set url
	 *
	 * @param string $url
	 *
	 * @return UrlInterface
	 */
	public function setUrl(?string $url): UrlInterface;

	/**
	 * Get url
	 *
	 * @return string
	 */
	public function getUrl(): ?string;

	/**
	 * Get value to urlize
	 *
	 * @return string
	 */
	public function getValueToUrlize(): string;
}