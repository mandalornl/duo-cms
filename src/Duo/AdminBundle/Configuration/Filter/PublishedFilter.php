<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\PageBundle\Form\Filter\PublishedFilterType;

class PublishedFilter extends AbstractFilter
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
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return PublishedFilterType::class;
	}
}
