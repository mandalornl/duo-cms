<?php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\SeoBundle\Entity\Robot;

class RobotFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager): void
	{
		/**
		 * @var User $user
		 */
		$user = $this->getReference('user');

		$content = <<<EOT
User-agent: *
Disallow: /admin/
Sitemap: {scheme}://{host}/sitemap.xml
EOT;

		$robot = (new Robot())
			->setContent($content)
			->setCreatedBy($user);

		$manager->persist($robot);
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
