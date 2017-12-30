<?php

namespace Duo\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Entity\Page;
use Duo\BehaviorBundle\Entity\SoftDeleteInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;
use Duo\BehaviorBundle\Repository\SortTrait;

class PageRepository extends EntityRepository
{
	use SortTrait;

	/**
	 * Get query builder
	 *
	 * @return QueryBuilder
	 */
	private function getQueryBuilder(): QueryBuilder
	{
		$builder = $this->createQueryBuilder('e');

		$reflectionClass = $this->getClassMetadata()->getReflectionClass();
		if ($reflectionClass->implementsInterface(VersionInterface::class))
		{
			$builder->andWhere('e.version = e.id');
		}

		if ($reflectionClass->implementsInterface(SoftDeleteInterface::class))
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		return $builder;
	}

	/**
	 * Find one by id
	 *
	 * @param int $id
	 *
	 * @return Page
	 */
	public function findById(int $id): ?Page
	{
		try
		{
			return $this->getQueryBuilder()
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
	 *
	 * @return Page
	 */
	public function findOneByUrl(string $url): ?Page
	{
		$builder = $this->getQueryBuilder();

		$reflectionClass = $this->getClassMetadata()->getReflectionClass();
		if ($reflectionClass->implementsInterface(TranslateInterface::class))
		{
			$builder
				->join('e.translations', 't')
				->andWhere('t.url = :url')
				->setParameter('url', $url);
		}
		else
		{
			$builder
				->andWhere('e.url = :url')
				->setParameter('url', $url);
		}

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
	 *
	 * @return Page
	 */
	public function findOneByName(string $name): ?Page
	{
		try
		{
			return $this->getQueryBuilder()
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