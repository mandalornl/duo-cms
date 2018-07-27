<?php

namespace Duo\PageBundle\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Duo\AdminBundle\Repository\AbstractEntityRepository;
use Duo\CoreBundle\Repository\SortTrait;
use Duo\CoreBundle\Repository\TreeTrait;
use Duo\PageBundle\Entity\PageInterface;

class PageRepository extends AbstractEntityRepository
{
	use SortTrait;
	use TreeTrait;

	/**
	 * PageRepository constructor
	 *
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		$entityClass = $registry->getManager()
			->getClassMetadata(PageInterface::class)
			->getName();

		parent::__construct($registry, $entityClass);
	}

	/**
	 * Find one by id
	 *
	 * @param int $id
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 *
	 * @throws \Throwable
	 */
	public function findById(int $id, string $locale = null): ?PageInterface
	{
		try
		{
			return $this->getQueryBuilder($locale)
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
	 * Find one by url
	 *
	 * @param string $url
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 *
	 * @throws \Throwable
	 */
	public function findOneByUrl(string $url, string $locale = null): ?PageInterface
	{
		$builder = $this->getQueryBuilder($locale);

		$builder
			->andWhere('t.url = :url')
			->setParameter('url', $url);

		try
		{
			return $builder
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
	 *
	 * @throws \Throwable
	 */
	public function findOneByName(string $name, string $locale = null): ?PageInterface
	{
		try
		{
			return $this->getQueryBuilder($locale)
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
	 * @return \DateTime
	 *
	 * @throws \Throwable
	 */
	public function findLastModifiedAt(): \DateTime
	{
		try
		{
			$dateTime = $this->getQueryBuilder()
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