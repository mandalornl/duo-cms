<?php

namespace Duo\SeoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Duo\SeoBundle\Entity\Robot;

class RobotRepository extends EntityRepository
{
	/**
	 * Find latest
	 *
	 * @return Robot
	 */
	public function findLatest(): ?Robot
	{
		try
		{
			return $this->createQueryBuilder('e')
				->orderBy('e.createdAt', 'DESC')
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}
}