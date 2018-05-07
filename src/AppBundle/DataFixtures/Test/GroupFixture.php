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
		if (count($manager->getRepository(Group::class)->findAll()))
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
				 'ROLE_CAN_VIEW',
				 'ROLE_CAN_EDIT',
				 'ROLE_CAN_DELETE',
				 'ROLE_CAN_PUBLISH'
			 ],
			 'Administrators' => [
				 'ROLE_ADMIN',
				 'ROLE_USER',
				 'ROLE_CAN_VIEW',
				 'ROLE_CAN_EDIT',
				 'ROLE_CAN_DELETE',
				 'ROLE_CAN_PUBLISH'
			 ],
			 'Users' => [
				 'ROLE_USER',
				 'ROLE_CAN_VIEW'
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