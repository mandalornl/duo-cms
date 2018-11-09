<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractFilter implements FilterInterface
{
	/**
	 * @var string
	 */
	protected $property;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $alias;

	/**
	 * @var QueryBuilder
	 */
	protected $builder;

	/**
	 * @var array
	 */
	protected $data = [];

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
		$this->label = $label;
		$this->alias = $alias;
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
	public function setLabel(string $label): FilterInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel(): string
	{
		return $this->label;
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
	public function getAlias(): string
	{
		return $this->alias;
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
	 * {@inheritdoc}
	 */
	public function getHash(): string
	{
		return md5(static::class . $this->property);
	}

	/**
	 * Create illegal operator exception
	 *
	 * @param string $operator
	 *
	 * @return \LogicException
	 */
	protected function createIllegalOperatorException(string $operator): \LogicException
	{
		return new \LogicException("Illegal operator '{$operator}'");
	}

	/**
	 * Get param
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	abstract protected function getParam(array $data): string;
}