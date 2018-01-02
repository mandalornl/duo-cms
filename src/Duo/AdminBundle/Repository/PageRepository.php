<?php

namespace Duo\AdminBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Duo\AdminBundle\Entity\Page;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Repository\SortTrait;

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
}