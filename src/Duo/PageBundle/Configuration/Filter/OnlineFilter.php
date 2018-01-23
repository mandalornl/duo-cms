<?php

namespace Duo\PageBundle\Configuration\Filter;

use Duo\AdminBundle\Configuration\Filter\AbstractFilter;
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

		$id = 'online_' . md5($this->property);

		if (!(int)$data['value'])
		{
			$this->builder
				->andWhere(
					"({$this->alias}.publishAt IS NULL OR {$this->alias}.publishAt > :{$id}) OR " .
					"({$this->alias}.unpublishAt IS NOT NULL AND {$this->alias}.unpublishAt <= :{$id})"
				);
		}
		else
		{
			$this->builder
				->andWhere(
					"{$this->alias}.publishAt IS NOT NULL AND {$this->alias}.publishAt <= :{$id} AND " .
					"({$this->alias}.unpublishAt IS NULL OR {$this->alias}.unpublishAt > :{$id})"
				);
		}

		$this->builder->setParameter($id, new \DateTime());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return OnlineFilterType::class;
	}
}