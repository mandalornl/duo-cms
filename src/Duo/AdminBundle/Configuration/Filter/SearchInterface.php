<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

interface SearchInterface
{
	/**
	 * Build search
	 *
	 * @param QueryBuilder $builder
	 * @param Orx $orX
	 * @param string $keyword
	 */
	public function buildSearch(QueryBuilder $builder, Orx $orX, string $keyword): void;
}
