<?php

namespace Duo\AdminBundle\Configuration;

class Field implements FieldInterface
{
	/**
	 * @var string
	 */
	private $label;

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
	 * @param string $label
	 * @param string $property
	 * @param bool $sortable [optional]
	 * @param string $template [optional]
	 */
	public function __construct(string $label, string $property, bool $sortable = true, string $template = null)
	{
		$this->label = $label;
		$this->property = $property;
		$this->sortable = $sortable;
		$this->template = $template;
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