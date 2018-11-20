<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\NumericFilterType;

class NumericFilter extends AbstractFilter
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
			case 'equals':
				$builder
					->andWhere("{$this->alias}.{$this->property} = :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'notEquals':
				$builder
					->andWhere("{$this->alias}.{$this->property} <> :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'greaterOrEquals':
				$builder
					->andWhere("{$this->alias}.{$this->property} >= :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'greater':
				$builder
					->andWhere("{$this->alias}.{$this->property} > :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'less':
				$builder
					->andWhere("{$this->alias}.{$this->property} < :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'lessOrEquals':
				$builder
					->andWhere("{$this->alias}.{$this->property} <= :{$param}")
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
		return NumericFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(array $data): string
	{
		return 'num_' . md5("{$data['operator']}_{$this->property}");
	}
}