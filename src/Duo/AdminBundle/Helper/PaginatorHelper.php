<?php

namespace Duo\AdminBundle\Helper;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorHelper
{
	/**
	 * @var QueryBuilder
	 */
	private $builder;

	/**
	 * @var int
	 */
	private $page = 1;

	/**
	 * @var int
	 */
	private $limit = 12;

	/**
	 * @var int
	 */
	private $adjacent = 2;

	/**
	 * @var int
	 */
	private $count = 0;

	/**
	 * @var array
	 */
	private $result;

	/**
	 * PaginatorHelper constructor
	 *
	 * @param QueryBuilder $builder
	 */
	public function __construct(QueryBuilder $builder)
	{
		$this->builder = $builder;
	}

	/**
	 * Create view
	 *
	 * @param bool $fetchJoinCollection [optional]
	 *
	 * @return PaginatorHelper
	 */
	public function createView(bool $fetchJoinCollection = true): PaginatorHelper
	{
		$offset = max(0, $this->page - 1) * $this->limit;

		$query = $this->builder
			->setFirstResult($offset)
			->setMaxResults($this->limit)
			->getQuery();

		$paginator = new Paginator($query, $fetchJoinCollection);

		$this->count = $paginator->count();
		$this->result = $paginator->getIterator();

		return $this;
	}

	/**
	 * Set query builder
	 *
	 * @param QueryBuilder $builder
	 *
	 * @return PaginatorHelper
	 */
	public function setQueryBuilder(QueryBuilder $builder): PaginatorHelper
	{
		$this->builder = $builder;

		return $this;
	}

	/**
	 * Get query builder
	 *
	 * @return QueryBuilder
	 */
	public function getQueryBuilder(): QueryBuilder
	{
		return $this->builder;
	}

	/**
	 * Set page
	 *
	 * @param int $page
	 *
	 * @return PaginatorHelper
	 */
	public function setPage(int $page = null): PaginatorHelper
	{
		$this->page = $page ?: $this->page;

		return $this;
	}

	/**
	 * Get page
	 *
	 * @return int
	 */
	public function getPage(): int
	{
		return $this->page;
	}

	/**
	 * Set limit
	 *
	 * @param int $limit
	 *
	 * @return PaginatorHelper
	 */
	public function setLimit(int $limit = null): PaginatorHelper
	{
		$this->limit = $limit ?: $this->limit;

		return $this;
	}

	/**
	 * Get limit
	 *
	 * @return int
	 */
	public function getLimit(): int
	{
		return $this->limit;
	}

	/**
	 * Set adjacent
	 *
	 * @param int $adjacent
	 *
	 * @return PaginatorHelper
	 */
	public function setAdjacent(int $adjacent): PaginatorHelper
	{
		$this->adjacent = $adjacent;

		return $this;
	}

	/**
	 * Get adjacent
	 *
	 * @return int
	 */
	public function getAdjacent(): int
	{
		return $this->adjacent;
	}

	/**
	 * Get total
	 *
	 * @return int
	 */
	public function getCount(): int
	{
		return $this->count;
	}

	/**
	 * Get total pages
	 *
	 * @return int
	 */
	public function getPageCount(): int
	{
		return ceil($this->count / $this->limit);
	}

	/**
	 * Get result
	 *
	 * @return iterable
	 */
	public function getResult(): iterable
	{
		return $this->result;
	}

	/**
	 * Can paginate
	 *
	 * @return bool
	 */
	public function canPaginate(): bool
	{
		return $this->getPageCount() > 1;
	}

	/**
	 * Get previous page
	 *
	 * @return int
	 */
	public function getPreviousPage(): int
	{
		return max(1, $this->page - 1);
	}

	/**
	 * Get next page
	 *
	 * @return int
	 */
	public function getNextPage(): int
	{
		return min($this->getPageCount(), $this->page + 1);
	}

	/**
	 * Get begin
	 *
	 * @return int
	 */
	public function getBegin(): int
	{
		return max(1, $this->page - $this->adjacent);
	}

	/**
	 * Get end
	 *
	 * @return int
	 */
	public function getEnd(): int
	{
		return min($this->getPageCount(), $this->page + $this->adjacent);
	}

	/**
	 * get hide begin
	 *
	 * @return int
	 */
	public function getHideBegin(): int
	{
		return $this->page - ($this->adjacent + 1);
	}

	/**
	 * Get hide end
	 *
	 * @return int
	 */
	public function getHideEnd(): int
	{
		return $this->getPageCount() - ($this->page + $this->adjacent);
	}
}