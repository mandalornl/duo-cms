<?php

namespace Duo\AdminBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Menu implements MenuInterface
{
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
	private $uniqid;

	/**
	 * @var string
	 */
	private $url;

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
	 * Item constructor
	 */
	public function __construct()
	{
		$this->children = new ArrayCollection();
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
	public function setIcon(string $icon = null): MenuInterface
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
	public function setUniqid(string $uniqid): MenuInterface
	{
		$this->uniqid = $uniqid;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUniqid(): ?string
	{
		return $this->uniqid;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setUrl(string $url = null): MenuInterface
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
	public function setActive(bool $active = false): MenuInterface
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActive(): bool
	{
		return $this->active;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParent(MenuInterface $parent = null): MenuInterface
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
		$this->children[$menuItem->getUniqid()] = $menuItem;

		$menuItem->setParent($this);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeChild(MenuInterface $menuItem): MenuInterface
	{
		$this->children->removeElement($menuItem);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getChildren()
	{
		return $this->children;
	}
}