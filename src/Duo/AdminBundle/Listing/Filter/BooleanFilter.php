<?php

namespace Duo\AdminBundle\Listing\Filter;

use Duo\AdminBundle\Form\Filter\BooleanFilterType;

class BooleanFilter extends AbstractFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function apply(): void
	{
		$data = $this->getData();

		if (!isset($data['value']))
		{
			return;
		}

		$param = $this->getParam();

		$this->builder
			->andWhere("{$this->alias}.{$this->property} = :{$param}")
			->setParameter($param, (int)$data['value']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return BooleanFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(): string
	{
		return 'bool_' . md5($this->property);
	}
}