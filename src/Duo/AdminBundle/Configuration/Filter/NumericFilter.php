<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\NumericFilterType;

class NumericFilter extends AbstractFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function buildFilter(QueryBuilder $builder, array $data): void
	{
		if (empty($data['value']) || empty($data['operator']))
		{
			return;
		}

		$pid = $this->getParamId($data);

		switch ($data['operator'])
		{
			case 'equals':
				$builder->andWhere("{$this->alias}.{$this->property} = :{$pid}");
				break;

			case 'notEquals':
				$builder->andWhere("{$this->alias}.{$this->property} <> :{$pid}");
				break;

			case 'greaterOrEquals':
				$builder->andWhere("{$this->alias}.{$this->property} >= :{$pid}");
				break;

			case 'greater':
				$builder->andWhere("{$this->alias}.{$this->property} > :{$pid}");
				break;

			case 'less':
				$builder->andWhere("{$this->alias}.{$this->property} < :{$pid}");
				break;

			case 'lessOrEquals':
				$builder->andWhere("{$this->alias}.{$this->property} <= :{$pid}");
				break;

			default:
				throw $this->createIllegalOperatorException($data['operator']);
		}

		$builder->setParameter($pid, $data['value']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return NumericFilterType::class;
	}
}
