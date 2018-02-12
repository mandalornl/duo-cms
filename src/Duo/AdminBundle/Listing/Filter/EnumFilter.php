<?php

namespace Duo\AdminBundle\Listing\Filter;

use Duo\AdminBundle\Form\Filter\EnumFilterType;

class EnumFilter extends AbstractFilter
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
			case 'contains':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} IN(:{$param})")
					->setParameter($param, $data['value']);
				break;

			case 'notContains':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} NOT IN(:{$param})")
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
		return EnumFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(): string
	{
		$data = $this->getData();

		return 'enum_' . md5("{$data['operator']}_{$this->property}");
	}
}