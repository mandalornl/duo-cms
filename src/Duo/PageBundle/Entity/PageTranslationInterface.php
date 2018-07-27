<?php

namespace Duo\PageBundle\Entity;

use Duo\CoreBundle\Entity\PublishInterface;
use Duo\CoreBundle\Entity\SlugInterface;
use Duo\CoreBundle\Entity\UrlInterface;
use Duo\NodeBundle\Entity\NodeTranslationInterface;
use Duo\PartBundle\Entity\EntityPartInterface;
use Duo\SeoBundle\Entity\SeoInterface;

interface PageTranslationInterface extends NodeTranslationInterface,
										   SlugInterface,
										   UrlInterface,
										   PublishInterface,
										   SeoInterface,
										   EntityPartInterface
{
	/**
	 * Set title
	 *
	 * @param string $title
	 *
	 * @return PageTranslationInterface
	 */
	public function setTitle(string $title = null): PageTranslationInterface;

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle(): ?string;

	/**
	 * Set visibleMenu
	 *
	 * @param bool $visibleMenu
	 *
	 * @return PageTranslationInterface
	 */
	public function setVisibleMenu(bool $visibleMenu): PageTranslationInterface;

	/**
	 * Get visibleMenu
	 *
	 * @return bool
	 */
	public function getVisibleMenu(): bool;
}