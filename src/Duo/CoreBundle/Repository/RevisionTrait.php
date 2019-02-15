<?php

namespace Duo\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;

trait RevisionTrait
{
	/**
	 * {@inheritdoc}
	 */
	public function nameExists(string $name): bool
	{
		try
		{
			/**
			 * @var EntityRepository $this
			 */
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
