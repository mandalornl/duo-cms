<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\SecurityBundle\Entity\Role;

class RoleFixture extends Fixture
{
	/**
	 * {@inheritDoc}
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
			 'Can create' => 'CAN_CREATE',
			 'Can read' => 'CAN_READ',
			 'Can update' => 'CAN_UPDATE',
			 'Can delete' => 'CAN_DELETE'
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
