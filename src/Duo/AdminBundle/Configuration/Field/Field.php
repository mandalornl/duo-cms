<?php

namespace Duo\AdminBundle\Configuration\Field;

use Doctrine\ORM\QueryBuilder;

class Field implements FieldInterface
{
	/**
	 * @var string
	 */
	private $property;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $alias;

	/**
	 * @var bool
	 */
	private $sortable = true;

	/**
	 * @var string
	 */
	private $template;

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
	 * {@inheritdoc}
	 */
	public function setProperty(string $property): FieldInterface
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
	public function setLabel(string $label): FieldInterface
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
	public function setAlias(string $alias): FieldInterface
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
	public function setSortable(bool $sortable): FieldInterface
	{
		$this->sortable = $sortable;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isSortable(): bool
	{
		return $this->sortable;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTemplate(string $template): FieldInterface
	{
		$this->template = $template;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTemplate(): ?string
	{
		return $this->template;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHash(): string
	{
		return md5(static::class . $this->property);
	}

	/**
	 * {@inheritdoc}
	 */
	public function applySorting(QueryBuilder $builder, string $order): void
	{
		$builder->orderBy("{$this->alias}.{$this->property}", $order);
	}

	/**
	 * {@inheritdoc}
	 */
	public function applyExport(QueryBuilder $builder): void
	{
		$builder->addSelect("{$this->alias}.{$this->property}");
	}
}
