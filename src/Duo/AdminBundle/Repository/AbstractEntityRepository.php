<?php

namespace Duo\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\PublishInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;

abstract class AbstractEntityRepository extends EntityRepository
{
	/**
	 * Create default query builder
	 *
	 * @param string $locale [optional]
	 *
	 * @return QueryBuilder
	 */
	public function createDefaultQueryBuilder(string $locale = null): QueryBuilder
	{
		$builder = $this->createQueryBuilder('e');

		$reflectionClass = $this->getClassMetadata()->getReflectionClass();

		// which is not deleted
		if ($reflectionClass->implementsInterface(DeleteInterface::class))
		{
			$builder->andWhere('e.deletedAt IS NULL');
		}

		// and is published
		if ($reflectionClass->implementsInterface(PublishInterface::class))
		{
			$this->andWherePublished($builder);
		}

		// has translations
		if ($reflectionClass->implementsInterface(TranslateInterface::class))
		{
			// using locale
			if ($locale !== null)
			{
				$builder
					->join('e.translations', 't', Join::WITH, 't.locale = :locale')
					->setParameter('locale', $locale);
			}
			// or all
			else
			{
				$builder->join('e.translations', 't');
			}

			$translationReflectionClass = $this->getEntityManager()
				->getClassMetadata($this->getClassMetadata()->getAssociationTargetClass('translations'))
				->getReflectionClass();

			// and is published
			if ($translationReflectionClass->implementsInterface(PublishInterface::class))
			{
				$this->andWherePublished($builder, 't');
			}
		}

		return $builder;
	}

	/**
	 * Add publication
	 *
	 * @param QueryBuilder $builder
	 * @param string $alias [optional]
	 */
	private function andWherePublished(QueryBuilder $builder, string $alias = 'e'): void
	{
		$pid = uniqid('now_');

		$builder
			->andWhere("({$alias}.publishAt <= :{$pid} AND ({$alias}.unpublishAt > :{$pid} OR {$alias}.unpublishAt IS NULL))")
			->setParameter($pid, new \DateTime());
	}
}
