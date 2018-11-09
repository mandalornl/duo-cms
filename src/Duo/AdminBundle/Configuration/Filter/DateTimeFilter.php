<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\DateTimeFilterType;

class DateTimeFilter extends AbstractFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function applyFilter(QueryBuilder $builder, array $data): void
	{
		if (empty($data['value']) || empty($data['operator']))
		{
			return;
		}

		$param = $this->getParam($data);

		switch ($data['operator'])
		{
			case 'before':
				$builder
					->andWhere("{$this->alias}.{$this->property} < :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'after':
				$builder
					->andWhere("{$this->alias}.{$this->property} > :{$param}")
					->setParameter($param, $data['value']);
				break;

			default:
				throw $this->createIllegalOperatorException($data['operator']);
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
	protected function getParam(array $data): string
	{
		return 'date_' . md5("{$data['operator']}_{$this->property}");
	}
}