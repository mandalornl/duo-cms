<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\SecurityBundle\Entity\GroupInterface;
use Duo\SecurityBundle\Entity\User;

class UserFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if (($user = $manager->getRepository(User::class)->findOneBy(['username' => 'admin'])) !== null)
		{
			return;
		}

		/**
		 * @var GroupInterface $group
		 */
		$group = $this->getReference('group');

		$user = (new User())
			->setName('Admin')
			->setActive(true)
			->setEmail('admin@example.com')
			->addGroup($group);

		$user
			->setUsername('admin')
			->setPlainPassword('admin');

		$user->setCreatedBy($user);

		$manager->persist($user);
		$manager->flush();

		$this->addReference('user', $user);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			GroupFixture::class
		];
	}
}