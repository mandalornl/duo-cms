<?php

namespace Duo\TaxonomyBundle\Configuration\Filter;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Filter\EnumFilter;
use Duo\TaxonomyBundle\Form\Filter\TaxonomyFilterType;

class TaxonomyFilter extends EnumFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function applyFilter(QueryBuilder $builder, array $data): void
	{
		if (!count($data['value']) || empty($data['operator']))
		{
			return;
		}

		$pid = $this->getParamId($data);
		$alias = uniqid('taxonomy_');

		switch ($data['operator'])
		{
			case 'contains':
				$builder->join(
					"{$this->alias}.taxonomies",
					$alias,
					Join::WITH,
					"{$alias}.{$this->property} IN(:{$pid})"
				);
				break;

			case 'notContains':
				$builder->join(
					"{$this->alias}.taxonomies",
					$alias,
					Join::WITH,
					"{$alias}.{$this->property} NOT IN(:{$pid})"
				);
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
		return TaxonomyFilterType::class;
	}
}
