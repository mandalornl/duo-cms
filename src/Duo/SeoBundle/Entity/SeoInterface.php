<?php

namespace Duo\SeoBundle\Entity;

interface SeoInterface
{
	/**
	 * Set metaTitle
	 *
	 * @param string $metaTitle
	 *
	 * @return SeoInterface
	 */
	public function setMetaTitle(string $metaTitle = null): SeoInterface;

	/**
	 * Get metaTitle
	 *
	 * @return string
	 */
	public function getMetaTitle(): ?string;

	/**
	 * Set metaKeywords
	 *
	 * @param string $metaKeywords
	 *
	 * @return SeoInterface
	 */
	public function setMetaKeywords(string $metaKeywords = null): SeoInterface;

	/**
	 * Get metaKeywords
	 *
	 * @return string
	 */
	public function getMetaKeywords(): ?string;

	/**
	 * Set metaDescription
	 *
	 * @param string $metaDescription
	 *
	 * @return SeoInterface
	 */
	public function setMetaDescription(string $metaDescription = null): SeoInterface;

	/**
	 * Get metaDescription
	 *
	 * @return string
	 */
	public function getMetaDescription(): ?string;

	/**
	 * Set metaRobots
	 *
	 * @param string $metaRobots
	 *
	 * @return SeoInterface
	 */
	public function setMetaRobots(string $metaRobots = null): SeoInterface;

	/**
	 * Get metaRobots
	 *
	 * @return string
	 */
	public function getMetaRobots(): ?string;
}