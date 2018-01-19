<?php

namespace Duo\FormBundle\Form;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\TranslationType;
use Duo\FormBundle\Entity\Form;
use Duo\TaxonomyBundle\Form\TaxonomyChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormListingType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$tabs = $builder->create('tabs', TabsType::class)
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
				->add('taxonomies', TaxonomyChoiceType::class, [
					'required' => false
				])
			)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.form.tab.content'
				])
				->add('translations', TranslationType::class, [
					'entry_type' => FormTranslationListingType::class
				])
			);

		$builder->add($tabs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Form::class
		]);
	}
}