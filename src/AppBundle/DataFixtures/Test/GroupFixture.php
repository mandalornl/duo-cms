<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Entity\Role;

class GroupFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(Group::class)->count([]))
		{
			return;
		}

		$roles = $manager->getRepository(Role::class)->findAll();

		foreach ($roles as $index => $role)
		{
			$roles[$role->getRole()] = $role;

			unset($roles[$index]);
		}

		foreach ([
			 'Super Administrators' => [
				 'ROLE_SUPER_ADMIN',
				 'ROLE_ADMIN',
				 'ROLE_USER',
				 'ROLE_CAN_CREATE',
				 'ROLE_CAN_READ',
				 'ROLE_CAN_UPDATE',
				 'ROLE_CAN_DELETE'
			 ],
			 'Administrators' => [
				 'ROLE_ADMIN',
				 'ROLE_USER',
				 'ROLE_CAN_CREATE',
				 'ROLE_CAN_READ',
				 'ROLE_CAN_UPDATE',
				 'ROLE_CAN_DELETE'
			 ],
			 'Users' => [
				 'ROLE_USER',
				 'ROLE_CAN_READ'
			 ],
			 'Guests' => [
				 'IS_AUTHENTICATED_ANONYMOUSLY'
			 ]
		 ] as $groupName => $roleNames)
		{
			$group = (new Group())
				->setName($groupName);

			foreach ($roleNames as $roleName)
			{
				$group->addRole($roles[$roleName]);
			}

			$manager->persist($group);

			if ($groupName === 'Super Administrators')
			{
				$this->addReference('group', $group);
			}
		}

		$manager->flush();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			RoleFixture::class
		];
	}
}