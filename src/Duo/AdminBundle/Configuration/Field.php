<?php

namespace Duo\AdminBundle\Configuration;

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
	 * @var string
	 */
	private $template;

	/**
	 * Field constructor
	 *
	 * @param string $title
	 * @param string $property
	 * @param bool $sortable [optional]
	 * @param string $template [optional]
	 */
	public function __construct(string $title, string $property, bool $sortable = true, string $template = null)
	{
		$this->title = $title;
		$this->property = $property;
		$this->sortable = $sortable;
		$this->template = $template;
	}

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

	/**
	 * {@inheritdoc}
	 */
	public function setTemplate(string $template): FieldInterface
	{
		$this->template = $template;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTemplate(): ?string
	{
		return $this->template;
	}
}