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
	 * {@inheritdoc}
	 */
	public function setId(string $id): MenuInterface
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId(): ?string
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setLabel(string $label): MenuInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel(): ?string
	{
		return $this->label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setIcon(?string $icon): MenuInterface
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIcon(): ?string
	{
		return $this->icon;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setUrl(?string $url): MenuInterface
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTarget(?string $target): MenuInterface
	{
		$this->target = $target;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTarget(): ?string
	{
		return $this->target;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setActive(bool $active): MenuInterface
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isActive(): bool
	{
		return $this->active;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParent(?MenuInterface $parent): MenuInterface
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): ?MenuInterface
	{
		return $this->parent;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addChild(MenuInterface $menuItem): MenuInterface
	{
		$menuItem->setParent($this);

		$this->getChildren()->set($menuItem->getId(), $menuItem);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeChild(MenuInterface $menuItem): MenuInterface
	{
		$this->getChildren()->removeElement($menuItem);

		$menuItem->setParent(null);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getChildren(): Collection
	{
		return $this->children = $this->children ?: new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setBreadcrumbs(array $breadcrumbs): MenuInterface
	{
		$this->breadcrumbs = $breadcrumbs;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBreadcrumbs(): array
	{
		return $this->breadcrumbs;
	}
}
