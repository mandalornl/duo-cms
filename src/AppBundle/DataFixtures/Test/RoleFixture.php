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
		if ($manager->getRepository(Role::class)->count([]))
		{
			return;
		}

		foreach ([
			 'Super Administrator' => 'ROLE_SUPER_ADMIN',
			 'Administrator' => 'ROLE_ADMIN',
			 'User' => 'ROLE_USER',
			 'Anonymous' => 'IS_AUTHENTICATED_ANONYMOUSLY',
			 'Can create' => 'ROLE_CAN_CREATE',
			 'Can read' => 'ROLE_CAN_READ',
			 'Can update' => 'ROLE_CAN_UPDATE',
			 'Can delete' => 'ROLE_CAN_DELETE'
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
