<?php

namespace Duo\AdminBundle\Configuration\Filter;

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

		$id = 'bool_' . md5($this->property);

		$this->builder
			->andWhere("{$this->alias}.{$this->property} = :{$id}")
			->setParameter($id, (int)$data['value']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return BooleanFilterType::class;
	}
}