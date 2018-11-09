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
	 * @var bool
	 */
	private $sortable = true;

	/**
	 * @var \Closure
	 */
	private $sortableCallback;

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var string
	 */
	private $alias;

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
	public function getSortable(): bool
	{
		return $this->sortable;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setSortableCallback(\Closure $sortableCallback): FieldInterface
	{
		$this->sortableCallback = $sortableCallback;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSortableCallback(): ?\Closure
	{
		return $this->sortableCallback;
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
		if ($this->sortableCallback !== null)
		{
			call_user_func_array($this->sortableCallback, [ $this, $builder, $order ]);

			return;
		}

		$builder->orderBy("{$this->alias}.{$this->property}", $order);
	}
}