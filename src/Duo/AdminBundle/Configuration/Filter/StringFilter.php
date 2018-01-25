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

		$id = 'str_' . md5("{$data['operator']}_{$this->property}");
		
		switch ($data['operator'])
		{
			case 'contains':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$id}")
					->setParameter($id, QueryHelper::escapeLike($data['value']));
				break;

			case 'notContains':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$id}")
					->setParameter($id, QueryHelper::escapeLike($data['value']));
				break;

			case 'equals':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} = :{$id}")
					->setParameter($id, $data['value']);
				break;

			case 'notEquals':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} <> :{$id}")
					->setParameter($id, $data['value']);
				break;

			case 'startsWith':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$id}")
					->setParameter($id, QueryHelper::escapeLike($data['value'], '%s%%'));
				break;

			case 'endsWith':
				$this->builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$id}")
					->setParameter($id, QueryHelper::escapeLike($data['value'], '%%%s'));
				break;

			default:
				throw new \LogicException("Illegal operator '{$data['operator']}'");
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return StringFilterType::class;
	}
}