<?php

namespace Duo\FormBundle\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Duo\AdminBundle\Repository\AbstractEntityRepository;
use Duo\FormBundle\Entity\Form;

class FormRepository extends AbstractEntityRepository
{
	/**
	 * FormRepository constructor
	 *
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Form::class);
	}

	/**
	 * Find one by name
	 *
	 * @param string $name
	 *
	 * @return Form
	 */
	public function findOneByName(string $name): ?Form
	{
		try
		{
			return $this->createDefaultQueryBuilder()
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