<?php

namespace Duo\FormBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractTextFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder
			->add('required', CheckboxType::class, [
				'label' => 'duo.form.form.form_part.required.label',
				'required' => false
			])
			->add('placeholder', TextType::class, [
				'label' => 'duo.form.form.form_part.placeholder.label',
				'required' => false
			])
			->add('errorMessage', TextType::class, [
				'label' => 'duo.form.form.form_part.error_message.label',
				'required' => false
			]);
	}
}