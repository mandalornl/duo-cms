<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Duo\AdminBundle\Form\Filter\StringFilterType;
use Duo\AdminBundle\Helper\ORM\QueryHelper;

class StringFilter extends AbstractFilter
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
					->andWhere("{$this->alias}.{$this->property} LIKE :{$param}")
					->setParameter($param, QueryHelper::escapeLike($data['value']));
				break;

			case 'notContains':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$param}")
					->setParameter($param, QueryHelper::escapeLike($data['value']));
				break;

			case 'equals':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} = :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'notEquals':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} <> :{$param}")
					->setParameter($param, $data['value']);
				break;

			case 'startsWith':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$param}")
					->setParameter($param, QueryHelper::escapeLike($data['value'], '%s%%'));
				break;

			case 'endsWith':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$param}")
					->setParameter($param, QueryHelper::escapeLike($data['value'], '%%%s'));
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
		return StringFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(): string
	{
		$data = $this->getData();

		return 'str_' . md5("{$data['operator']}_{$this->property}");
	}
}