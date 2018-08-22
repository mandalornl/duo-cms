<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
	/**
	 * Set property
	 *
	 * @param string $property
	 *
	 * @return FilterInterface
	 */
	public function setProperty(string $property): FilterInterface;

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
	 * @return FilterInterface
	 */
	public function setLabel(string $label): FilterInterface;

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel(): ?string;

	/**
	 * Set alias
	 *
	 * @param string $alias
	 *
	 * @return FilterInterface
	 */
	public function setAlias(string $alias): FilterInterface;

	/**
	 * Get alias
	 *
	 * @return string
	 */
	public function getAlias(): ?string;

	/**
	 * Set query builder
	 *
	 * @param QueryBuilder $builder
	 *
	 * @return FilterInterface
	 */
	public function setQueryBuilder(QueryBuilder $builder): FilterInterface;

	/**
	 * Get query builder
	 *
	 * @return QueryBuilder
	 */
	public function getQueryBuilder(): ?QueryBuilder;

	/**
	 * Set data
	 *
	 * @param array $data
	 *
	 * @return FilterInterface
	 */
	public function setData(array $data): FilterInterface;

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function getData(): array;

	/**
	 * Apply
	 *
	 * @throws \LogicException
	 */
	public function apply(): void;

	/**
	 * Get form type
	 *
	 * @return string
	 */
	public function getFormType(): string;

	/**
	 * Set formOptions
	 *
	 * @param array $formOptions
	 *
	 * @return FilterInterface
	 */
	public function setFormOptions(array $formOptions): FilterInterface;

	/**
	 * Get form options
	 *
	 * @return array
	 */
	public function getFormOptions(): array;

	/**
	 * Get hash
	 *
	 * @return string
	 */
	public function getHash(): string;
}