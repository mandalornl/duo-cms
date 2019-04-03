<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\SecurityBundle\Entity\GroupInterface;
use Duo\SecurityBundle\Entity\UserInterface;

class UserFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(UserInterface::class)->count([]))
		{
			return;
		}

		/**
		 * @var GroupInterface $group
		 */
		$group = $this->getReference('group');

		/**
		 * @var UserInterface $user
		 */
		$user = $manager->getClassMetadata(UserInterface::class)->getReflectionClass()->newInstance();
		$user->setName('Admin');
		$user->setActive(true);
		$user->setEmail('admin@example.com');
		$user->addGroup($group);
		$user->setUsername('admin');
		$user->setPlainPassword('admin');

		$manager->persist($user);
		$manager->flush();

		$this->addReference('user', $user);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencies(): array
	{
		return [
			GroupFixture::class
		];
	}
}
