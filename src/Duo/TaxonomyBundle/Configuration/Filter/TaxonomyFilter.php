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

		$param = $this->getParam($data);

		switch ($data['operator'])
		{
			case 'contains':
				$builder
					->join("{$this->alias}.taxonomies", 'taxonomy', Join::WITH, "taxonomy.{$this->property} IN(:{$param})")
					->setParameter($param, $data['value']);
				break;

			case 'notContains':
				$builder
					->join("{$this->alias}.taxonomies", 'taxonomy', Join::WITH, "taxonomy.{$this->property} NOT IN(:{$param})")
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
		return TaxonomyFilterType::class;
	}
}