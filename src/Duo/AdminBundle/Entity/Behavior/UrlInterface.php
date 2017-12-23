<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface UrlInterface
{
	/**
	 * Set url
	 *
	 * @param string $url
	 *
	 * @return UrlInterface
	 */
	public function setUrl(string $url = null): UrlInterface;

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