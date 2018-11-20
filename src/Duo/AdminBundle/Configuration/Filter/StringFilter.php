<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\StringFilterType;
use Duo\AdminBundle\Tools\ORM\Query;

class StringFilter extends AbstractFilter implements SearchInterface
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
					->andWhere("{$this->alias}.{$this->property} LIKE :{$param}")
					->setParameter($param, Query::escapeLike($data['value']));
				break;

			case 'notContains':
				$builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$param}")
					->setParameter($param, Query::escapeLike($data['value']));
				break;

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

			case 'startsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$param}")
					->setParameter($param, Query::escapeLike($data['value'], '%s%%'));
				break;

			case 'notStartsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$param}")
					->setParameter($param, Query::escapeLike($data['value'], '%s%%'));
				break;

			case 'endsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$param}")
					->setParameter($param, Query::escapeLike($data['value'], '%%%s'));
				break;

			case 'notEndsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$param}")
					->setParameter($param, Query::escapeLike($data['value'], '%%%s'));
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
		return StringFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(array $data): string
	{
		return 'str_' . md5("{$data['operator']}_{$this->property}");
	}

	/**
	 * {@inheritdoc}
	 */
	public function applySearch(QueryBuilder $builder, Orx $orX, string $keyword): void
	{
		$orX->add("{$this->alias}.{$this->property} LIKE :keyword");
	}
}