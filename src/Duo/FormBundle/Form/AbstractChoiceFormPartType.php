<?php

namespace Duo\FormBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractChoiceFormPartType extends AbstractTextFormPartType
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
}