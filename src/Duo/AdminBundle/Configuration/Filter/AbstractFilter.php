<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractFilter implements FilterInterface
{
	/**
	 * @var string
	 */
	protected $alias = 'e';

	/**
	 * @var QueryBuilder
	 */
	protected $builder;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var string
	 */
	protected $propertyName;

	/**
	 * @var array
	 */
	protected $formOptions = [];

	/**
	 * AbstractFilter constructor
	 *
	 * @param string $propertyName
	 * @param string $label
	 * @param string $alias [optional]
	 */
	public function __construct(string $propertyName, string $label, string $alias = 'e')
	{
		$this->propertyName = $propertyName;
		$this->alias = $alias;
		$this->formOptions = [
			'label' => $label
		];
	}

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

	/**
	 * {@inheritdoc}
	 */
	public function setData(array $data): FilterInterface
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPropertyName(string $propertyName): FilterInterface
	{
		$this->propertyName = $propertyName;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPropertyName(): string
	{
		return $this->propertyName;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setFormOptions(array $formOptions): FilterInterface
	{
		$this->formOptions = $formOptions;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormOptions(): array
	{
		return $this->formOptions;
	}
}