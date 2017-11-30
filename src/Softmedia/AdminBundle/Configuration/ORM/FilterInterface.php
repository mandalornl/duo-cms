<?php

namespace Softmedia\AdminBundle\Configuration\ORM;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
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
}