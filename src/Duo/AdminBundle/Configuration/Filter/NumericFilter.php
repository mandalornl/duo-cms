<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Duo\AdminBundle\Form\Filter\NumericFilterType;

class NumericFilter extends AbstractFilter
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
			case 'equals':
				$this->builder
					->andWherE("{$this->alias}.{$this->property} = :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'greaterOrEquals':
				$this->builder
					->andWherE("{$this->alias}.{$this->property} >= :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'greater':
				$this->builder
					->andWherE("{$this->alias}.{$this->property} > :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'less':
				$this->builder
					->andWherE("{$this->alias}.{$this->property} < :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'lessOrEquals':
				$this->builder
					->andWherE("{$this->alias}.{$this->property} <= :{$param}")
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
		return NumericFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(): string
	{
		$data = $this->getData();

		return 'num_' . md5("{$data['operator']}_{$this->property}");
	}
}