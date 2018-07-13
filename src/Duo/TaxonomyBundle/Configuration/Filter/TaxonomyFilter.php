<?php

namespace Duo\TaxonomyBundle\Configuration\Filter;

use Doctrine\ORM\Query\Expr\Join;
use Duo\AdminBundle\Configuration\Filter\EnumFilter;
use Duo\TaxonomyBundle\Form\Filter\TaxonomyFilterType;

class TaxonomyFilter extends EnumFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function apply(): void
	{
		$data = $this->getData();

		if (!count($data['value']) || empty($data['operator']))
		{
			return;
		}

		$param = $this->getParam();

		switch ($data['operator'])
		{
			case 'contains':
				$this->builder
					->join("{$this->alias}.taxonomies", 'tx', Join::WITH, "tx.{$this->property} IN(:{$param})")
					->setParameter($param, $data['value']);
				break;

			case 'notContains':
				$this->builder
					->join("{$this->alias}.taxonomies", 'tx', Join::WITH, "tx.{$this->property} NOT IN(:{$param})")
					->setParameter($param, $data['value']);
				break;

			default:
				throw $this->createIllegalOperatorException();
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