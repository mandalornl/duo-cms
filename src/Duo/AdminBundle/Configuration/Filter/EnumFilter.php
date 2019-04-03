<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\EnumFilterType;

class EnumFilter extends AbstractFilter
{
	/**
	 * {@inheritDoc}
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
			case 'contains':
				$builder->andWhere("{$this->alias}.{$this->property} IN(:{$pid})");
				break;

			case 'notContains':
				$builder->andWhere("{$this->alias}.{$this->property} NOT IN(:{$pid})");
				break;

			default:
				throw $this->createIllegalOperatorException($data['operator']);
		}

		$builder->setParameter($pid, $data['value']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormType(): string
	{
		return EnumFilterType::class;
	}
}
