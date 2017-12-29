<?php

namespace Duo\SecurityBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function loadUserByUsername($username): ?UserInterface
	{
		return $this->createQueryBuilder('u')
			->where('u.username = :username OR u.email = :username')
			->setParameter('username', $username)
			->getQuery()
			->getOneOrNullResult();
	}
}