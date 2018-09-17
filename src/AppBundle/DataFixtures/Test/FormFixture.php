<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Entity\FormPart\ChoiceFormPart;
use Duo\FormBundle\Entity\FormPart\EmailFormPart;
use Duo\FormBundle\Entity\FormPart\SubmitFormPart;
use Duo\FormBundle\Entity\FormPart\PrivacyFormPart;
use Duo\FormBundle\Entity\FormPart\TextareaFormPart;
use Duo\FormBundle\Entity\FormPart\TextFormPart;

class FormFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(Form::class)->count([]))
		{
			return;
		}

		$user = $this->getReference('user');

		$form = new Form();
		$form->setName('Contact');
		$form->setEmailFrom('noreply@example.com');
		$form->setEmailTo('info@example.com');
		$form->setKeepSubmissions(true);
		$form->setSendSubmissionsTo('submission@example.com');
		$form->setCreatedBy($user);

		$form->translate('nl')
			->setSubject('Bedankt!')
			->setMessage('<p>Bedankt voor het invullen van het formulier.</p>')
			->addPart(
				(new ChoiceFormPart())
					->setLabel('Aanhef')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
					->setChoices("Dhr\nMevr")
			)
			->addPart(
				(new TextFormPart())
					->setLabel('Naam')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
					->setRequired(true)
			)
			->addPart(
				(new EmailFormPart())
					->setLabel('E-mailadres')
					->setWeight(2)
					->setSection('main')
					->setCreatedBy($user)
					->setRequired(true)
			)
			->addPart(
				(new TextareaFormPart())
					->setLabel('Opmerkingen')
					->setWeight(3)
					->setSection('main')
					->setCreatedBy($user)
					->setRequired(true)
			)
			->addPart(
				(new PrivacyFormPart())
					->setLabel('Ik ga akkoord met de privacyverklaring')
					->setWeight(4)
					->setSection('main')
					->setCreatedBy($user)
					->setErrorMessage('Ga akkoord met de privacyverklaring')
					->setRequired(true)
			)
			->addPart(
				(new SubmitFormPart())
					->setLabel('Versturen')
					->setWeight(5)
					->setSection('main')
					->setCreatedBy($user)
			);

		$form->translate('en')
			->setSubject('Thanks!')
			->setMessage('<p>Thank you for filling out the form.</p>')
			->addPart(
				(new ChoiceFormPart())
					->setLabel('Salutation')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
					->setChoices("Mr\nMs\nMrs")
			)
			->addPart(
				(new TextFormPart())
					->setLabel('Name')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
					->setRequired(true)
			)
			->addPart(
				(new EmailFormPart())
					->setLabel('Email')
					->setWeight(2)
					->setSection('main')
					->setCreatedBy($user)
					->setRequired(true)
			)
			->addPart(
				(new TextareaFormPart())
					->setLabel('Remarks')
					->setWeight(3)
					->setSection('main')
					->setCreatedBy($user)
					->setRequired(true)
			)
			->addPart(
				(new PrivacyFormPart())
					->setLabel('I agree to the privacy statement')
					->setWeight(4)
					->setSection('main')
					->setCreatedBy($user)
					->setErrorMessage('Agree to the privacy statment')
					->setRequired(true)
			)
			->addPart(
				(new SubmitFormPart())
					->setLabel('Send')
					->setWeight(5)
					->setSection('main')
					->setCreatedBy($user)
			);

		$form->mergeNewTranslations();

		$manager->persist($form);
		$manager->flush();

		$this->addReference('form', $form);
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
