<?php

namespace Duo\AdminBundle\Configuration\Field;

use Doctrine\ORM\QueryBuilder;

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
	 * Get hash
	 *
	 * @return string
	 */
	public function getHash(): string;

	/**
	 * Apply sorting
	 *
	 * @param QueryBuilder $builder
	 * @param string $order
	 */
	public function applySorting(QueryBuilder $builder, string $order): void;

	/**
	 * Apply export
	 *
	 * @param QueryBuilder $builder
	 */
	public function applyExport(QueryBuilder $builder): void;
};