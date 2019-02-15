<?php

namespace Duo\SeoBundle\Entity\Property;

interface SeoInterface
{
	/**
	 * Set metaTitle
	 *
	 * @param string $metaTitle
	 *
	 * @return SeoInterface
	 */
	public function setMetaTitle(?string $metaTitle): SeoInterface;

	/**
	 * Get metaTitle
	 *
	 * @return string
	 */
	public function getMetaTitle(): ?string;

	/**
	 * Set metaKeywords
	 *
	 * @param string[] $metaKeywords
	 *
	 * @return SeoInterface
	 */
	public function setMetaKeywords(?array $metaKeywords): SeoInterface;

	/**
	 * Get metaKeywords
	 *
	 * @return string[]
	 */
	public function getMetaKeywords(): ?array;

	/**
	 * Set metaDescription
	 *
	 * @param string $metaDescription
	 *
	 * @return SeoInterface
	 */
	public function setMetaDescription(?string $metaDescription): SeoInterface;

	/**
	 * Get metaDescription
	 *
	 * @return string
	 */
	public function getMetaDescription(): ?string;

	/**
	 * Set metaRobots
	 *
	 * @param string[] $metaRobots
	 *
	 * @return SeoInterface
	 */
	public function setMetaRobots(?array $metaRobots): SeoInterface;

	/**
	 * Get metaRobots
	 *
	 * @return string[]
	 */
	public function getMetaRobots(): ?array;
}
