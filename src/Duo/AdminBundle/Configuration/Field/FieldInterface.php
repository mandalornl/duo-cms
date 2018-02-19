<?php

namespace Duo\AdminBundle\Configuration\Field;

interface FieldInterface
{
	/**
	 * Set property
	 *
	 * @param string $property
	 *
	 * @return FieldInterface
	 */
	public function setProperty(string $property): FieldInterface;

	/**
	 * Get property
	 *
	 * @return string
	 */
	public function getProperty(): string;

	/**
	 * Set label
	 *
	 * @param string $label
	 *
	 * @return FieldInterface
	 */
	public function setLabel(string $label): FieldInterface;

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel(): string;

	/**
	 * Set sortable
	 *
	 * @param bool $sortable
	 *
	 * @return FieldInterface
	 */
	public function setSortable(bool $sortable): FieldInterface;

	/**
	 * Get sortable
	 *
	 * @return bool
	 */
	public function getSortable(): bool;

	/**
	 * Set template
	 *
	 * @param string $template
	 *
	 * @return FieldInterface
	 */
	public function setTemplate(string $template): FieldInterface;

	/**
	 * Get template
	 *
	 * @return string
	 */
	public function getTemplate(): ?string;

	/**
	 * Set alias
	 *
	 * @param string $alias
	 *
	 * @return FieldInterface
	 */
	public function setAlias(string $alias): FieldInterface;

	/**
	 * Get alias
	 *
	 * @return string
	 */
	public function getAlias(): string;
};