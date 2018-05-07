<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\MediaBundle\Entity\Folder;

class FolderFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if (($folder = $manager->getRepository(Folder::class)->findOneBy(['name' => 'Files'])) !== null)
		{
			return;
		}

		$user = $this->getReference('user');

		$folder = (new Folder())
			->setName('Files')
			->setCreatedBy($user);

		$manager->persist($folder);
		$manager->flush();

		$this->addReference('folder', $folder);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			UserFixture::class
		];
	}
}