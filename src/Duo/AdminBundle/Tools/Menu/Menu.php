<?php

namespace Duo\AdminBundle\Tools\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Menu implements MenuInterface
{
	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $target;

	/**
	 * @var bool
	 */
	private $active = false;

	/**
	 * @var MenuInterface
	 */
	private $parent;

	/**
	 * @var Collection
	 */
	private $children;

	/**
	 * @var MenuInterface[]
	 */
	private $breadcrumbs = [];

	/**
	 * {@inheritDoc}
	 */
	public function setId(string $id): MenuInterface
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getId(): ?string
	{
		return $this->id;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setLabel(string $label): MenuInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLabel(): ?string
	{
		return $this->label;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setIcon(?string $icon): MenuInterface
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIcon(): ?string
	{
		return $this->icon;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setUrl(?string $url): MenuInterface
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setTarget(?string $target): MenuInterface
	{
		$this->target = $target;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTarget(): ?string
	{
		return $this->target;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setActive(bool $active): MenuInterface
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isActive(): bool
	{
		return $this->active;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setParent(?MenuInterface $parent): MenuInterface
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): ?MenuInterface
	{
		return $this->parent;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addChild(MenuInterface $menuItem): MenuInterface
	{
		$menuItem->setParent($this);

		$this->getChildren()->set($menuItem->getId(), $menuItem);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeChild(MenuInterface $menuItem): MenuInterface
	{
		$this->getChildren()->removeElement($menuItem);

		$menuItem->setParent(null);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getChildren(): Collection
	{
		return $this->children = $this->children ?: new ArrayCollection();
	}

	/**
	 * {@inheritDoc}
	 */
	public function setBreadcrumbs(array $breadcrumbs): MenuInterface
	{
		$this->breadcrumbs = $breadcrumbs;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBreadcrumbs(): array
	{
		return $this->breadcrumbs;
	}
}
