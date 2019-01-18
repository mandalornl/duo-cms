<?php

namespace Duo\AdminBundle\Configuration\Field;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

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
	 * Is sortable
	 *
	 * @return bool
	 */
	public function isSortable(): bool;

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
	 * Build sorting
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 * @param string $order
	 */
	public function buildSorting(Request $request, QueryBuilder $builder, string $order): void;

	/**
	 * Build export
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 */
	public function buildExport(Request $request, QueryBuilder $builder): void;
};
