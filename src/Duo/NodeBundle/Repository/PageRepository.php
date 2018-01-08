<?php

namespace Duo\NodeBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Duo\AdminBundle\Repository\AbstractEntityRepository;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Repository\SortTrait;
use Duo\NodeBundle\Entity\Page;

class PageRepository extends AbstractEntityRepository
{
	use SortTrait;

	/**
	 * Find one by id
	 *
	 * @param int $id
	 * @param string $locale [optional]
	 *
	 * @return Page
	 */
	public function findById(int $id, string $locale = null): ?Page
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
	 * @return Page
	 */
	public function findOneByUrl(string $url, string $locale = null): ?Page
	{
		$builder = $this->getQueryBuilder($locale);

		$alias = 'e';

		$reflectionClass = $this->getClassMetadata()->getReflectionClass();
		if ($reflectionClass->implementsInterface(TranslateInterface::class))
		{
			$alias = 't';
		}

		$builder
			->andWhere("{$alias}.url = :url")
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
	 * @return Page
	 */
	public function findOneByName(string $name, string $locale = null): ?Page
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