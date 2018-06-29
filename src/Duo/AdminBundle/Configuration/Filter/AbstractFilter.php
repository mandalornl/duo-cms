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
	protected $property;

	/**
	 * @var array
	 */
	protected $formOptions = [];

	/**
	 * AbstractFilter constructor
	 *
	 * @param string $property
	 * @param string $label
	 * @param string $alias [optional] e = entity, t = translation
	 */
	public function __construct(string $property, string $label, string $alias = 'e')
	{
		$this->property = $property;
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
	public function setProperty(string $property): FilterInterface
	{
		$this->property = $property;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getProperty(): string
	{
		return $this->property;
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

	/**
	 * Create illegal operator exception
	 *
	 * @return \LogicException
	 */
	protected function createIllegalOperatorException(): \LogicException
	{
		$data = $this->getData();

		return new \LogicException("Illegal operator '{$data['operator']}'");
	}

	/**
	 * Get param
	 *
	 * @return string
	 */
	abstract protected function getParam(): string;
}