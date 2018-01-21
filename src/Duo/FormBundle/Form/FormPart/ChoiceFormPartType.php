<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\ChoiceFormPart;
use Duo\FormBundle\Form\AbstractFormPartType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChoiceFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder
			->add('choices', TextareaType::class, [
				'label' => 'duo.form.form.form_part.choices.label',
				'constraints' => [
					new NotBlank()
				]
			])
			->add('expanded', CheckboxType::class, [
				'label' => 'duo.form.form.form_part.expanded.label',
				'required' => false
			])
			->add('multiple', CheckboxType::class, [
				'label' => 'duo.form.form.form_part.multiple.label',
				'required' => false
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => ChoiceFormPart::class,
			'model_class' => ChoiceFormPart::class
		]);
	}
}