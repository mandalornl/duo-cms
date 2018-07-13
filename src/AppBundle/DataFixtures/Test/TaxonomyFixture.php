<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\TaxonomyBundle\Entity\Taxonomy;

class TaxonomyFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if (count($taxonomies = $manager->getRepository(Taxonomy::class)->findAll()))
		{
			return;
		}

		$user = $this->getReference('user');

		foreach ([
			'taxonomy_files' => [
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
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			UserFixture::class
		];
	}
}