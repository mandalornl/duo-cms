<?php

namespace Duo\FormBundle\Form\Listing;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\TranslationType;
use Duo\FormBundle\Entity\Form;
use Duo\PageBundle\Form\PageAutoCompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class FormType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.form.tab.content'
				])
				->add('translations', TranslationType::class, [
					'entry_type' => FormTranslationType::class,
					'constraints' => [
						new Valid()
					]
				])
			)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.form.tab.properties'
				])
				->add('name', TextType::class, [
					'label' => 'duo.form.form.form.name.label'
				])
				->add('emailFrom', EmailType::class, [
					'label' => 'duo.form.form.form.email_from.label'
				])
				->add('emailTo', EmailType::class, [
					'label' => 'duo.form.form.form.email_to.label'
				])
				->add('keepSubmissions', CheckboxType::class, [
					'label' => 'duo.form.form.form.keep_submissions.label',
					'required' => false
				])
				->add('sendSubmissionsTo', EmailType::class, [
					'required' => false,
					'label' => 'duo.form.form.form.send_submissions_to.label'
				])
				->add('page', PageAutoCompleteType::class, [
					'required' => false,
					'label' => 'duo.form.form.form.page.label'
				])
			);

		$builder->add($tabs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Form::class
		]);
	}
}