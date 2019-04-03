<?php

namespace Duo\FormBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\PageBundle\Form\Type\PageAutoCompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertiesTabType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'duo_form.form.form.name.label'
			])
			->add('emailFrom', EmailType::class, [
				'label' => 'duo_form.form.form.email_from.label'
			])
			->add('emailTo', EmailType::class, [
				'label' => 'duo_form.form.form.email_to.label'
			])
			->add('keepSubmissions', CheckboxType::class, [
				'label' => 'duo_form.form.form.keep_submissions.label',
				'required' => false
			])
			->add('sendSubmissionsTo', EmailType::class, [
				'required' => false,
				'label' => 'duo_form.form.form.send_submissions_to.label'
			])
			->add('page', PageAutoCompleteType::class, [
				'required' => false,
				'label' => 'duo_form.form.form.page.label'
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_form.tab.properties'
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
