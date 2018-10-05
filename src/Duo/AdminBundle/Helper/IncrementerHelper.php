<?php

namespace Duo\AdminBundle\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Duo\AdminBundle\Tools\ORM\Query;

class IncrementerHelper
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * IncrementerHelper constructor
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * Get incremented value
	 *
	 * @param object $entity
	 * @param string $propertyName
	 *
	 * @return string
	 */
	public function getValue(object $entity, string $propertyName): ?string
	{
		$classMetadata = $this->manager->getClassMetadata(get_class($entity));

		if (($reflectionClass = $classMetadata->getReflectionClass()) === null ||
			!$reflectionClass->hasProperty($propertyName))
		{
			return null;
		}

		$property = $reflectionClass->getProperty($propertyName);
		$property->setAccessible(true);

		$value = $property->getValue($entity);

		if (empty($value))
		{
			return null;
		}

		try
		{
			$dql = <<<SQL
SELECT COUNT(e) FROM {$reflectionClass->getName()} e WHERE e.{$propertyName} = :value
SQL;
			$found = (int)$this->manager->createQuery($dql)
					->setParameter('value', $value)
					->getSingleScalarResult() !== 0;

			if (!$found)
			{
				return $value;
			}
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}

		$valueWithoutIncrementation = preg_replace('#\d+$#', '', $value);

		$dql = <<<SQL
SELECT e.{$propertyName} FROM {$reflectionClass->getName()} e
WHERE e.{$propertyName} LIKE :likeValue AND e.{$propertyName} <> :value 
ORDER BY LENGTH(e.{$propertyName}) DESC, e.{$propertyName} DESC
SQL;

		$values = $this->manager->createQuery($dql)
			->setParameter('likeValue', Query::escapeLike($valueWithoutIncrementation, '%s%%'))
			->setParameter('value', $value)
			->getScalarResult();

		$values = array_column($values, $propertyName);
		$values = array_filter($values, function(string $value) use ($valueWithoutIncrementation)
		{
			return preg_match("#^{$valueWithoutIncrementation}-\d+$#", $value);
		});

		if (!count($values))
		{
			return "{$value}-1";
		}

		return preg_replace_callback('#(\d+)$#', function(array $matches)
		{
			return (int)$matches[1] + 1;
		}, current($values));
	}
}