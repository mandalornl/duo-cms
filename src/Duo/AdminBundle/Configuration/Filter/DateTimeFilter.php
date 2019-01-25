<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\DateTimeFilterType;

class DateTimeFilter extends AbstractFilter
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
			case 'before':
				$builder->andWhere("{$this->alias}.{$this->property} < :{$pid}");
				break;

			case 'after':
				$builder->andWhere("{$this->alias}.{$this->property} > :{$pid}");
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
		return DateTimeFilterType::class;
	}
}
