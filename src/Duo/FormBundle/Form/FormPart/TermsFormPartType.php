<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TermsFormPart;
use Duo\FormBundle\Form\AbstractFormPartType;
use Duo\PageBundle\Form\PageAutoCompleteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermsFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('required', CheckboxType::class, [
				'label' => 'duo.form.form.form_part.required.label',
				'required' => false
			])
			->add('errorMessage', TextType::class, [
				'label' => 'duo.form.form.form_part.error_message.label',
				'required' => false
			])
			->add('page', PageAutoCompleteType::class, [
				'label' => 'duo.form.form.form_part.page.label',
				'required' => false
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => TermsFormPart::class,
			'model_class' => TermsFormPart::class
		]);
	}
}