<?php

namespace Duo\CoreBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, string $indexBy = null)
 */
trait RevisionTrait
{
	/**
	 * {@inheritDoc}
	 */
	public function revisionNameExists(string $name): bool
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
					->select('e.id')
					->where('e.name = :name')
					->setParameter('name', $name)
					->getQuery()
					->getOneOrNullResult(Query::HYDRATE_SINGLE_SCALAR) > 0;
		}
		catch (NonUniqueResultException $e)
		{
			return false;
		}
	}
}
