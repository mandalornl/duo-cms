<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Entity\FormPart\ChoiceFormPart;
use Duo\FormBundle\Entity\FormPart\EmailFormPart;
use Duo\FormBundle\Entity\FormPart\SubmitFormPart;
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
		$form->setSendResultTo('result@example.com');
		$form->setCreatedBy($user);

		$form->translate('nl')
			->setSubject('Bedankt!')
			->setMessage('<p>Bedankt voor het invullen van het formulier.</p>')
			->addPart(
				(new ChoiceFormPart())
					->setLabel('Aanhef')
					->setChoices("Dhr\nMevr")
			)
			->addPart(
				(new TextFormPart())
					->setLabel('Naam')
					->setRequired(true)
			)
			->addPart(
				(new EmailFormPart())
					->setLabel('E-mailadres')
					->setRequired(true)
			)
			->addPart(
				(new TextareaFormPart())
					->setLabel('Opmerkingen')
					->setRequired(true)
			)
			->addPart(
				(new SubmitFormPart())
					->setLabel('Versturen')
			);

		$form->translate('en')
			->setSubject('Thanks!')
			->setMessage('<p>Thank you for filling out the form.</p>')
			->addPart(
				(new ChoiceFormPart())
					->setLabel('Salutation')
					->setChoices("Mr\nMs\nMrs")
			)
			->addPart(
				(new TextFormPart())
					->setLabel('Name')
					->setRequired(true)
			)
			->addPart(
				(new EmailFormPart())
					->setLabel('Email')
					->setRequired(true)
			)
			->addPart(
				(new TextareaFormPart())
					->setLabel('Remarks')
					->setRequired(true)
			)
			->addPart(
				(new SubmitFormPart())
					->setLabel('Send')
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
