<?php

namespace Duo\AdminBundle\Configuration;

class Field implements FieldInterface
{
	/**
	 * @var string
	 */
	private $property;

	/**
	 * @var string
	 */
	private $label;

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
	 * @param string $property
	 * @param string $label
	 * @param bool $sortable [optional]
	 * @param string $template [optional]
	 */
	public function __construct(string $property, string $label, bool $sortable = true, string $template = null)
	{
		$this->property = $property;
		$this->label = $label;
		$this->sortable = $sortable;
		$this->template = $template;
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
	public function setLabel(string $label): FieldInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel(): string
	{
		return $this->label;
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