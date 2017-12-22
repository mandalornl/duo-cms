<?php

namespace Duo\AdminBundle\Configuration\ORM;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractFilter implements FilterInterface
{
	/**
	 * @var string
	 */
	protected $alias;

	/**
	 * @var QueryBuilder
	 */
	protected $builder;

	/**
	 * {@inheritdoc}
	 */
	public function setAlias(string $alias): FilterInterface
	{
		$this->alias = $alias;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAlias(): ?string
	{
		return $this->alias;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setQueryBuilder(QueryBuilder $builder): FilterInterface
	{
		$this->builder = $builder;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQueryBuilder(): ?QueryBuilder
	{
		return $this->builder;
	}
}