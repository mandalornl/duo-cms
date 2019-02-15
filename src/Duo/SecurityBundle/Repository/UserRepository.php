<?php

namespace Duo\SecurityBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Duo\SecurityBundle\Entity\UserInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface as CoreUserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function loadUserByUsername($username): ?CoreUserInterface
	{
		try
		{
			return $this->createQueryBuilder('e')
				->where('e.username = :username OR e.email = :username')
				->setParameter('username', $username)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException $e)
		{
			return null;
		}
	}

	/**
	 * Find one by password token
	 *
	 * @param string $token
	 *
	 * @return UserInterface
	 */
	public function findOneByPasswordToken(string $token): ?UserInterface
	{
		try
		{
			$tokenLifetime = (new \DateTime())->modify('-1 day');

			return $this->createQueryBuilder('e')
				->where('e.passwordToken = :token AND :tokenLifetime < e.passwordTokenRequestedAt')
				->setParameter('token', $token)
				->setParameter('tokenLifetime', $tokenLifetime)
				->getQuery()
				->getOneOrNullResult();
		}
		catch (NonUniqueResultException | \Exception $e)
		{
			return null;
		}
	}

	/**
	 * Check whether or not password token exists
	 *
	 * @param string $token
	 *
	 * @return bool
	 */
	public function passwordTokenExists(string $token): bool
	{
		try
		{
			return (int)$this->createQueryBuilder('e')
				->select('e.id')
				->where('e.passwordToken = :token')
				->setParameter('token', $token)
				->getQuery()
				->getOneOrNullResult(Query::HYDRATE_SINGLE_SCALAR) > 0;
		}
		catch (NonUniqueResultException $e)
		{
			return false;
		}
	}
}
