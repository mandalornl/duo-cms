<?php

namespace Duo\PageBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Duo\AdminBundle\Repository\AbstractEntityRepository;
use Duo\CoreBundle\Repository\SortInterface;
use Duo\CoreBundle\Repository\SortTrait;
use Duo\CoreBundle\Repository\TreeInterface;
use Duo\CoreBundle\Repository\TreeTrait;
use Duo\PageBundle\Entity\PageInterface;

class PageRepository extends AbstractEntityRepository implements SortInterface, TreeInterface
{
	use SortTrait;
	use TreeTrait;

	/**
	 * Find one by id
	 *
	 * @param int $id
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 */
	public function findOneById(int $id, string $locale = null): ?PageInterface
	{
		try
		{
			return $this->createDefaultQueryBuilder($locale)
				->andWhere('e.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getOneOrNullResult();

		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * Find on by uuid
	 *
	 * @param string $uuid
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 */
	public function findOneByUuid(string $uuid, string $locale = null): ?PageInterface
	{
		try
		{
			return $this->createDefaultQueryBuilder($locale)
				->andWhere('e.uuid = :uuid')
				->setParameter('uuid', $uuid)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * Find one by url
	 *
	 * @param string $url
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 */
	public function findOneByUrl(string $url, string $locale = null): ?PageInterface
	{
		try
		{
			return $this->createDefaultQueryBuilder($locale)
				->andWhere('t.url = :url')
				->setParameter('url', $url)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * Find one by name
	 *
	 * @param string $name
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 */
	public function findOneByName(string $name, string $locale = null): ?PageInterface
	{
		try
		{
			return $this->createDefaultQueryBuilder($locale)
				->andWhere('e.name = :name')
				->setParameter('name', $name)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * Find latest modified at
	 *
	 * @return \DateTimeInterface
	 *
	 * @throws \Throwable
	 */
	public function findLastModifiedAt(): \DateTimeInterface
	{
		try
		{
			$dateTime = $this->createDefaultQueryBuilder()
				->select('MAX(e.modifiedAt)')
				->getQuery()
				->getOneOrNullResult(Query::HYDRATE_SINGLE_SCALAR);

			if ($dateTime === null)
			{
				return new \DateTime();
			}

			return new \DateTime($dateTime);
		}
		catch (NonUniqueResultException $e)
		{
			return new \DateTime();
		}
	}
}
