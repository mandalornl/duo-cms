<?php

namespace Duo\BehaviorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;

trait TreeTrait
{
	/**
	 * Get offspring id's
	 *
	 * @param int|int[] $id
	 * @param bool $traverse [optional]
	 *
	 * @return int[]
	 */
	public function getOffspringIds($id, bool $traverse = true): array
	{
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('id', 'id', 'integer');

		/**
		 * @var EntityRepository $this
		 */
		$className = $this->getClassMetadata()->getTableName();

		$sql = <<<SQL
SELECT id FROM {$className} WHERE parent_id IN(:ids)
SQL;

		/**
		 * @var Query $query
		 */
		$query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

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
	 * Get parent id's
	 *
	 * @param int|int[] $id
	 * @param bool $traverse [optional]
	 *
	 * @return int[]
	 */
	public function getParentIds($id, bool $traverse = true): array
	{
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('parent_id', 'id', 'integer');

		/**
		 * @var EntityRepository $this
		 */
		$className = $this->getClassMetadata()->getTableName();

		$sql = <<<SQL
SELECT parent_id FROM {$className} WHERE id IN(:ids) AND parent_id IS NOT NULL
SQL;

		/**
		 * @var Query $query
		 */
		$query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

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