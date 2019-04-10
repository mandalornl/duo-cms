<?php

namespace Duo\CoreBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, string $indexBy = null)
 * @method ClassMetadata getClassMetadata()
 * @method EntityManagerInterface getEntityManager()
 */
trait TreeTrait
{
	/**
	 * {@inheritDoc}
	 */
	public function getOffspringIds(int $id, bool $traverse = true): array
	{
		$className = $this->getClassMetadata()->getName();

		$dql = <<<DQL
SELECT e.id FROM {$className} e WHERE e.parent IN (:ids)
DQL;

		$query = $this->getEntityManager()->createQuery($dql);

		$offspring = [];
		$iterations = 100;
		$ids = (array)$id;

		do
		{
			$query->setParameter('ids', $ids);
			$ids = array_column($query->getScalarResult(), 'id');

			$offspring = array_merge($offspring, $ids);
			if (!$traverse)
			{
				break;
			}
		} while (count($ids) && $iterations--);

		return array_map('intval', array_unique($offspring));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParentIds(int $id, bool $traverse = true): array
	{
		$className = $this->getClassMetadata()->getName();

		$dql = <<<DQL
SELECT IDENTITY(e.parent) id FROM {$className} e WHERE e.id IN(:ids) AND e.parent IS NOT NULL
DQL;

		$query = $this->getEntityManager()->createQuery($dql);

		$parents = [];
		$iterations = 100;
		$ids = (array)$id;

		do
		{
			$query->setParameter('ids', $ids);
			$ids = array_column($query->getScalarResult(), 'id');

			$parents = array_merge($parents, $ids);

			if (!$traverse)
			{
				break;
			}
		} while (count($ids) && $iterations--);

		return array_map('intval', array_unique($parents));
	}
}
