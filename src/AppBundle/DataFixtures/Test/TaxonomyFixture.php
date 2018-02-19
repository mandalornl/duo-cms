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

		$taxonomy = (new Taxonomy());
		$taxonomy->setCreatedBy($user);
		$taxonomy->translate('nl')->setName('Pagina');
		$taxonomy->translate('en')->setName('Page');
		$taxonomy->mergeNewTranslations();

		$manager->persist($taxonomy);
		$manager->flush();
	}

	/**
	 * {@inheritdoc}
	 */
	function getDependencies(): array
	{
		return [
			UserFixture::class
		];
	}
}