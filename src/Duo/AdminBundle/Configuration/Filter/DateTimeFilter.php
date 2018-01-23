<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Duo\AdminBundle\Form\Filter\DateTimeFilterType;

class DateTimeFilter extends AbstractFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function apply(): void
	{
		$data = $this->getData();

		if (empty($data['value']) || empty($data['operator']))
		{
			return;
		}

		$id = 'date_' . md5("{$data['operator']}_{$this->property}");

		switch ($data['operator'])
		{
			case 'before':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} < :{$id}")
					->setParameter($id, $data['value']);
				break;

			case 'after':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} > :{$id}")
					->setParameter($id, $data['value']);
				break;

			default:
				throw new \LogicException("Illegal operator '{$data['operator']}'");
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return DateTimeFilterType::class;
	}
}