<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\SecurityBundle\Entity\Role;

class RoleFixture extends Fixture
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if (count($manager->getRepository(Role::class)->findAll()))
		{
			return;
		}

		foreach ([
			 'Super Administrator' => 'ROLE_SUPER_ADMIN',
			 'Administrator' => 'ROLE_ADMIN',
			 'User' => 'ROLE_USER',
			 'Anonymous' => 'IS_AUTHENTICATED_ANONYMOUSLY',
			 'Can view' => 'ROLE_CAN_VIEW',
			 'Can edit' => 'ROLE_CAN_EDIT',
			 'Can delete' => 'ROLE_CAN_DELETE',
			 'Can publish' => 'ROLE_CAN_PUBLISH'
		 ] as $name => $roleName)
		{
			$role = (new Role())
				->setName($name)
				->setRole($roleName);

			$manager->persist($role);
		}

		$manager->flush();
	}
}