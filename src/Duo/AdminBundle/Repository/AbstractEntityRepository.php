<?php

namespace Duo\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\RevisionInterface;

abstract class AbstractEntityRepository extends EntityRepository
{
	/**
	 * Get query builder
	 *
	 * @param string $locale [optional]
	 *
	 * @return QueryBuilder
	 */
	protected function getQueryBuilder(string $locale = null): QueryBuilder
	{
		$builder = $this->createQueryBuilder('e');

		$reflectionClass = $this->getClassMetadata()->getReflectionClass();

		// use latest revision
		if ($reflectionClass->implementsInterface(RevisionInterface::class))
		{
			$builder->andWhere('e.revision = e.id');
		}

		// which is not deleted
		if ($reflectionClass->implementsInterface(DeleteInterface::class))
		{
			$builder->andWhere('e.deletedAt IS NULL');
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

			$translationReflectionClass = new \ReflectionClass(
				$this->getClassMetadata()->getAssociationTargetClass('translations')
			);

			// and is published
			if ($translationReflectionClass->implementsInterface(PublishInterface::class))
			{
				$this->andWherePublished($builder, 't');
			}
			else
			{
				$this->andWherePublished($builder);
			}
		}
		else
		{
			// and is published
			if ($reflectionClass->implementsInterface(PublishInterface::class))
			{
				$this->andWherePublished($builder);
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
	private function andWherePublished(QueryBuilder $builder, string $alias = 'e')
	{
		$builder
			->andWhere("({$alias}.publishAt <= :dateTime AND ({$alias}.unpublishAt > :dateTime OR {$alias}.unpublishAt IS NULL))")
			->setParameter('dateTime', new \DateTime());
	}
}