<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\SecurityBundle\Entity\UserInterface;
use Duo\TaxonomyBundle\Entity\Taxonomy;

class TaxonomyFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(Taxonomy::class)->count([]))
		{
			return;
		}

		/**
		 * @var UserInterface $user
		 */
		$user = $this->getReference('user');

		foreach ([
			'taxonomy-files' => [
				'en' => 'Files',
				'nl' => 'Bestanden'
			]
		 ] as $reference => $item)
		{
			$taxonomy = (new Taxonomy());
			$taxonomy->setCreatedBy($user);

			foreach ($item as $locale => $name)
			{
				$taxonomy->translate($locale)->setName($name);
			}

			$taxonomy->mergeNewTranslations();

			$manager->persist($taxonomy);

			$this->addReference($reference, $taxonomy);
		}

		$manager->flush();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencies(): array
	{
		return [
			UserFixture::class
		];
	}
}
