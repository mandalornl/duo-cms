<?php

namespace Duo\AdminBundle\Listing\Filter;

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

		$param = $this->getParam();

		switch ($data['operator'])
		{
			case 'before':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} < :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'after':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} > :{$param}")
					->setParameter($param, $data['value']);
				break;

			default:
				throw $this->getOperatorException();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return DateTimeFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(): string
	{
		$data = $this->getData();

		return 'date_' . md5("{$data['operator']}_{$this->property}");
	}
}