<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\BooleanFilterType;

class BooleanFilter extends AbstractFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function applyFilter(QueryBuilder $builder, array $data): void
	{
		if (!isset($data['value']))
		{
			return;
		}

		$param = $this->getParam($data);

		$builder
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
	protected function getParam(array $data): string
	{
		return 'bool_' . md5($this->property);
	}
}