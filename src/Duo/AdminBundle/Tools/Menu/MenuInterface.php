<?php

namespace Duo\AdminBundle\Tools\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface MenuInterface
{
	/**
	 * @var string
	 */
	const TARGET_BLANK = '_blank';

	/**
	 * @var string
	 */
	const TARGET_SELF = '_self';

	/**
	 * Set id
	 *
	 * @param string $id
	 *
	 * @return MenuInterface
	 */
	public function setId(string $id): MenuInterface;

	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getId(): ?string;

	/**
	 * Set label
	 *
	 * @param string $label
	 *
	 * @return MenuInterface
	 */
	public function setLabel(string $label): MenuInterface;

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel(): ?string;

	/**
	 * Set icon
	 *
	 * @param string $icon
	 *
	 * @return MenuInterface
	 */
	public function setIcon(?string $icon): MenuInterface;

	/**
	 * Get icon
	 *
	 * @return string
	 */
	public function getIcon(): ?string;

	/**
	 * Set url
	 *
	 * @param string $url
	 *
	 * @return MenuInterface
	 */
	public function setUrl(?string $url): MenuInterface;

	/**
	 * Get url
	 *
	 * @return string
	 */
	public function getUrl(): ?string;

	/**
	 * Set target
	 *
	 * @param string $target
	 *
	 * @return MenuInterface
	 */
	public function setTarget(?string $target): MenuInterface;

	/**
	 * Get target
	 *
	 * @return string
	 */
	public function getTarget(): ?string;

	/**
	 * Set active
	 *
	 * @param bool $active
	 *
	 * @return MenuInterface
	 */
	public function setActive(bool $active): MenuInterface;

	/**
	 * Is active
	 *
	 * @return bool
	 */
	public function isActive(): bool;

	/**
	 * Set parent
	 *
	 * @param MenuInterface $parent
	 *
	 * @return MenuInterface
	 */
	public function setParent(?MenuInterface $parent): MenuInterface;

	/**
	 * Get parent
	 *
	 * @return MenuInterface
	 */
	public function getParent(): ?MenuInterface;

	/**
	 * Add child
	 *
	 * @param MenuInterface $menuItem
	 *
	 * @return MenuInterface
	 */
	public function addChild(MenuInterface $menuItem): MenuInterface;

	/**
	 * Remove child
	 *
	 * @param MenuInterface $menuItem
	 *
	 * @return MenuInterface
	 */
	public function removeChild(MenuInterface $menuItem): MenuInterface;

	/**
	 * Get children
	 *
	 * @return ArrayCollection
	 */
	public function getChildren(): Collection;

	/**
	 * Set breadcrumbs
	 *
	 * @param MenuInterface[] $breadcrumbs
	 *
	 * @return MenuInterface
	 */
	public function setBreadcrumbs(array $breadcrumbs): MenuInterface;

	/**
	 * Get breadcrumbs
	 *
	 * @return MenuInterface[]
	 */
	public function getBreadcrumbs(): array;
}
