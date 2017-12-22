<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface UrlableInterface
{
	/**
	 * Set url
	 *
	 * @param string $url
	 *
	 * @return UrlableInterface
	 */
	public function setUrl(string $url = null): UrlableInterface;

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