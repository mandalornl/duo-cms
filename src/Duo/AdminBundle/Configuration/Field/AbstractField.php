<?php

namespace Duo\AdminBundle\Configuration\Field;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractField implements FieldInterface
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
	 * @var bool
	 */
	protected $sortable = true;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * Field constructor
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
	public function setProperty(string $property): FieldInterface
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
	public function setLabel(string $label): FieldInterface
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
	public function setAlias(string $alias): FieldInterface
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
	public function setSortable(bool $sortable): FieldInterface
	{
		$this->sortable = $sortable;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isSortable(): bool
	{
		return $this->sortable;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setTemplate(string $template): FieldInterface
	{
		$this->template = $template;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTemplate(): ?string
	{
		return $this->template;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHash(): string
	{
		return md5(static::class . $this->property);
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildSorting(Request $request, QueryBuilder $builder, string $order): void
	{
		$builder->orderBy("{$this->alias}.{$this->property}", $order);
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildExport(Request $request, QueryBuilder $builder): void
	{
		$builder->addSelect("{$this->alias}.{$this->property}");
	}
}
