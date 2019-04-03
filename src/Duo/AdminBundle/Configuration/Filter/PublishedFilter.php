<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\PageBundle\Form\Filter\PublishedFilterType;

class PublishedFilter extends AbstractFilter
{
	/**
	 * {@inheritDoc}
	 */
	public function buildFilter(QueryBuilder $builder, array $data): void
	{
		if (!isset($data['value']))
		{
			return;
		}

		$pid = $this->getParamId($data);

		$expression = <<<SQL
(
    {$this->alias}.publishAt IS NOT NULL AND 
    {$this->alias}.publishAt <= :{$pid} AND 
    (
    	{$this->alias}.unpublishAt IS NULL OR 
    	{$this->alias}.unpublishAt > :{$pid}
    )
)
SQL;

		$builder
			->andWhere((!(int)$data['value'] ? 'NOT ' : '') . $expression)
			->setParameter($pid, new \DateTime());
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormType(): string
	{
		return PublishedFilterType::class;
	}
}
