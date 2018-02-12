<?php

namespace Duo\PageBundle\Listing\Filter;

use Duo\AdminBundle\Listing\Filter\AbstractFilter;
use Duo\PageBundle\Form\Filter\OnlineFilterType;

class OnlineFilter extends AbstractFilter
{
	/**
	 * {@inheritdoc}
	 */
	public function apply(): void
	{
		$data = $this->getData();

		if (!isset($data['value']))
		{
			return;
		}

		$param = $this->getParam();

		if (!(int)$data['value'])
		{
			$this->builder
				->andWhere(
					"({$this->alias}.publishAt IS NULL OR {$this->alias}.publishAt > :{$param}) OR " .
					"({$this->alias}.unpublishAt IS NOT NULL AND {$this->alias}.unpublishAt <= :{$param})"
				);
		}
		else
		{
			$this->builder
				->andWhere(
					"{$this->alias}.publishAt IS NOT NULL AND {$this->alias}.publishAt <= :{$param} AND " .
					"({$this->alias}.unpublishAt IS NULL OR {$this->alias}.unpublishAt > :{$param})"
				);
		}

		$this->builder->setParameter($param, new \DateTime());
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
	protected function getParam(): string
	{
		return 'online_' . md5($this->property);
	}
}