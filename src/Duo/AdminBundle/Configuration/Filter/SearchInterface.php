<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

interface SearchInterface
{
	/**
	 * Apply search
	 *
	 * @param QueryBuilder $builder
	 * @param Orx $orX
	 * @param string $keyword
	 */
	public function applySearch(QueryBuilder $builder, Orx $orX, string $keyword): void;
}