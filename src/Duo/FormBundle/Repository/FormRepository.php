<?php

namespace Duo\FormBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Duo\AdminBundle\Repository\AbstractEntityRepository;
use Duo\FormBundle\Entity\Form;

class FormRepository extends AbstractEntityRepository
{
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
