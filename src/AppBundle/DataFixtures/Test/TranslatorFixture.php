<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\TranslatorBundle\Entity\Entry;

class TranslatorFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(Entry::class)->count([]))
		{
			return;
		}

		$user = $this->getReference('user');

		$entry = (new Entry())
			->setDomain('messages')
			->setKeyword('app.test')
			->setFlag(Entry::FLAG_NONE);

		$entry->setCreatedBy($user);

		$entry->translate('nl')->setText('Dit is een test');
		$entry->translate('en')->setText('This is a test');

		$entry->mergeNewTranslations();

		$manager->persist($entry);
		$manager->flush();
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