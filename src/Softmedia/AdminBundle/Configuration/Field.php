<?php

namespace Softmedia\AdminBundle\Configuration;

class Field implements FieldInterface
{
	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $property;

	/**
	 * @var bool
	 */
	private $sortable = true;

	/**
	 * {@inheritdoc}
	 */
	public function setTitle(string $title): FieldInterface
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setProperty(string $property): FieldInterface
	{
		$this->property = $property;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getProperty(): string
	{
		return $this->property;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setSortable(bool $sortable): FieldInterface
	{
		$this->sortable = $sortable;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSortable(): bool
	{
		return $this->sortable;
	}
}