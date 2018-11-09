<?php

namespace Duo\PageBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Filter\AbstractFilter;
use Duo\PageBundle\Form\Filter\OnlineFilterType;

class OnlineFilter extends AbstractFilter
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

		if (!(int)$data['value'])
		{
			$builder
				->andWhere(
					"({$this->alias}.publishAt IS NULL OR {$this->alias}.publishAt > :{$param}) OR " .
					"({$this->alias}.unpublishAt IS NOT NULL AND {$this->alias}.unpublishAt <= :{$param})"
				);
		}
		else
		{
			$builder
				->andWhere(
					"{$this->alias}.publishAt IS NOT NULL AND {$this->alias}.publishAt <= :{$param} AND " .
					"({$this->alias}.unpublishAt IS NULL OR {$this->alias}.unpublishAt > :{$param})"
				);
		}

		$builder->setParameter($param, new \DateTime());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return OnlineFilterType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getParam(array $data): string
	{
		return 'online_' . md5($this->property);
	}
}