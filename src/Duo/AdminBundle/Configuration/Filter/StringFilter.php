<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\StringFilterType;
use Duo\AdminBundle\Tools\ORM\Query;

class StringFilter extends AbstractFilter implements SearchInterface
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
				$builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$pid}")
					->setParameter($pid, Query::escapeLike($data['value']));
				break;

			case 'notContains':
				$builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$pid}")
					->setParameter($pid, Query::escapeLike($data['value']));
				break;

			case 'equals':
				$builder
					->andWhere("{$this->alias}.{$this->property} = :{$pid}")
					->setParameter($pid, $data['value']);
				break;

			case 'notEquals':
				$builder
					->andWhere("{$this->alias}.{$this->property} <> :{$pid}")
					->setParameter($pid, $data['value']);
				break;

			case 'startsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$pid}")
					->setParameter($pid, Query::escapeLike($data['value'], '%s%%'));
				break;

			case 'notStartsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$pid}")
					->setParameter($pid, Query::escapeLike($data['value'], '%s%%'));
				break;

			case 'endsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} LIKE :{$pid}")
					->setParameter($pid, Query::escapeLike($data['value'], '%%%s'));
				break;

			case 'notEndsWith':
				$builder
					->andWhere("{$this->alias}.{$this->property} NOT LIKE :{$pid}")
					->setParameter($pid, Query::escapeLike($data['value'], '%%%s'));
				break;

			default:
				throw $this->createIllegalOperatorException($data['operator']);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormType(): string
	{
		return StringFilterType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildSearch(QueryBuilder $builder, Orx $orX, string $keyword): void
	{
		$orX->add("{$this->alias}.{$this->property} LIKE :keyword");
	}
}
