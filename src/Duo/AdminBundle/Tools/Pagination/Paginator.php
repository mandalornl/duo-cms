<?php

namespace Duo\AdminBundle\Tools\Pagination;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator
{
	/**
	 * @var int
	 */
	private $defaultLimit = 12;

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
	private $limit;

	/**
	 * @var int
	 */
	private $adjacent = 2;

	/**
	 * @var bool
	 */
	private $fetchJoinCollection = true;

	/**
	 * @var int
	 */
	private $count = 0;

	/**
	 * @var array
	 */
	private $result;

	/**
	 * Paginator constructor
	 *
	 * @param QueryBuilder $builder
	 */
	public function __construct(QueryBuilder $builder)
	{
		$this->builder = $builder;
	}

	/**
	 * Get defaultLimit
	 *
	 * @return int
	 */
	public function getDefaultLimit(): int
	{
		return $this->defaultLimit;
	}

	/**
	 * Set query builder
	 *
	 * @param QueryBuilder $builder
	 *
	 * @return Paginator
	 */
	public function setQueryBuilder(QueryBuilder $builder): Paginator
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
	 * @return Paginator
	 */
	public function setPage(?int $page): Paginator
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
	 * @return Paginator
	 */
	public function setLimit(?int $limit): Paginator
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Get limit
	 *
	 * @return int
	 */
	public function getLimit(): int
	{
		return $this->limit ?: $this->defaultLimit;
	}

	/**
	 * Set adjacent
	 *
	 * @param int $adjacent
	 *
	 * @return Paginator
	 */
	public function setAdjacent(int $adjacent): Paginator
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
	 * Set fetchJoinCollection
	 *
	 * @param bool $fetchJoinCollection
	 *
	 * @return Paginator
	 */
	public function setFetchJoinCollection(bool $fetchJoinCollection): Paginator
	{
		$this->fetchJoinCollection = $fetchJoinCollection;

		return $this;
	}

	/**
	 * Get fetchJoinCollection
	 *
	 * @return bool
	 */
	public function getFetchJoinCollection(): bool
	{
		return $this->fetchJoinCollection;
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
		return ceil($this->count / $this->getLimit());
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

	/**
	 * Create view
	 *
	 * @return Paginator
	 */
	public function createView(): Paginator
	{
		$offset = max(0, $this->page - 1) * $this->getLimit();

		$query = $this->builder
			->setFirstResult($offset)
			->setMaxResults($this->getLimit())
			->getQuery();

		$paginator = new DoctrinePaginator($query, $this->fetchJoinCollection);

		$this->count = $paginator->count();
		$this->result = $paginator->getIterator();

		return $this;
	}
}
