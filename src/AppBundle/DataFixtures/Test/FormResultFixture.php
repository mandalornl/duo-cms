<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Entity\FormSubmission;
use Duo\SecurityBundle\Entity\UserInterface;

class FormResultFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(FormSubmission::class)->count([]))
		{
			return;
		}

		/**
		 * @var Form $form
		 */
		$form = $this->getReference('form');

		/**
		 * @var UserInterface $user
		 */
		$user = $this->getReference('user');

		foreach ([
			'nl' => [
				'Naam' => 'John Doe',
				'E-mailadres' => 'johndoe@example.com',
				'Aanhef' => 'Dhr',
				'Opmerkingen' => 'Lorem ipsum dolor sit amet',
				'Ik ga akkoord met de privacyverklaring' => 1
			],
			'en' => [
				'Name' => 'John Doe',
				'Email' => 'johndoe@example.com',
				'Salutation' => 'Mr',
				'Remarks' => 'Lorem ipsum dolor sit amet',
				'I agree to the privacy statement' => 1
			]
		 ] as $locale => $data)
		{
			$result = (new FormSubmission())
				->setForm($form)
				->setLocale($locale)
				->setName($form->getName())
				->setData($data);

			$result->setCreatedBy($user);

			$manager->persist($result);
		}

		$manager->flush();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencies(): array
	{
		return [
			FormFixture::class
		];
	}
}
