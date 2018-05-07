<?php

namespace Duo\AdminBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;

interface MenuInterface
{
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
	public function setIcon(string $icon = null): MenuInterface;

	/**
	 * Get icon
	 *
	 * @return string
	 */
	public function getIcon(): ?string;

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
	 * Set url
	 *
	 * @param string $url
	 *
	 * @return MenuInterface
	 */
	public function setUrl(string $url = null): MenuInterface;

	/**
	 * Get url
	 *
	 * @return string
	 */
	public function getUrl(): ?string;

	/**
	 * Set active
	 *
	 * @param bool $active
	 *
	 * @return MenuInterface
	 */
	public function setActive(bool $active = false): MenuInterface;

	/**
	 * Get active
	 *
	 * @return bool
	 */
	public function getActive(): bool;

	/**
	 * Set parent
	 *
	 * @param MenuInterface $parent
	 *
	 * @return MenuInterface
	 */
	public function setParent(MenuInterface $parent = null): MenuInterface;

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
	public function getChildren(): ArrayCollection;

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