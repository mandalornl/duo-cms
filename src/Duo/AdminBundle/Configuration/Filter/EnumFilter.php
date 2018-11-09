<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\EnumFilterType;

class EnumFilter extends AbstractFilter
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
			case 'contains':
				$builder
					->andWhere("{$this->alias}.{$this->property} IN(:{$param})")
					->setParameter($param, $data['value']);
				break;

			case 'notContains':
				$builder
					->andWhere("{$this->alias}.{$this->property} NOT IN(:{$param})")
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
		return EnumFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(array $data): string
	{
		return 'enum_' . md5("{$data['operator']}_{$this->property}");
	}
}