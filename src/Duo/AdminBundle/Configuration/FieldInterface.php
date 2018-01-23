<?php

namespace Duo\AdminBundle\Configuration;

interface FieldInterface
{
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
};