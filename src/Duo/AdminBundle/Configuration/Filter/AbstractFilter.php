<?php

namespace Duo\AdminBundle\Configuration\Filter;

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
	 * {@inheritDoc}
	 */
	public function setProperty(string $property): FilterInterface
	{
		$this->property = $property;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getProperty(): string
	{
		return $this->property;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setLabel(string $label): FilterInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setAlias(string $alias): FilterInterface
	{
		$this->alias = $alias;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAlias(): string
	{
		return $this->alias;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setData(array $data): FilterInterface
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setFormOptions(array $formOptions): FilterInterface
	{
		$this->formOptions = $formOptions;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormOptions(): array
	{
		return $this->formOptions;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUid(): string
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
	 * Get param id
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	protected function getParamId(array $data): string
	{
		return 'pid_' . md5(static::class . ($data['comparator'] ?? '') . $this->property);
	}
}
