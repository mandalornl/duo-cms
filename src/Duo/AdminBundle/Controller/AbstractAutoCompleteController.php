<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractAutoCompleteController extends Controller
{
	/**
	 * Set first result and max results
	 *
	 * @param Request $request
	 * @param QueryBuilder $builder
	 * @param int $defaultLimit [optional]
	 */
	protected function setFirstResultAndMaxResults(Request $request, QueryBuilder $builder, int $defaultLimit = 10): void
	{
		$limit = (int)$request->get('limit') ?: $defaultLimit;
		if ($limit <= 0)
		{
			return;
		}

		$builder->setMaxResults($limit);

		$offset = null;
		if (($page = (int)$request->get('page')))
		{
			$offset  = ($page - 1) * $limit;

			$builder->setFirstResult($offset);
		}
	}

	/**
	 * Get count
	 *
	 * @param QueryBuilder $builder
	 *
	 * @return int
	 */
	protected function getCount(QueryBuilder $builder): int
	{
		try
		{
			return (int)$builder->select('COUNT(DISTINCT e.id)')
				->getQuery()
				->getSingleScalarResult();
		}
		catch (NoResultException | NonUniqueResultException $e)
		{
			return 0;
		}
	}
}