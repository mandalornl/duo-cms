<?php

namespace Duo\FormBundle\Form\FormPart;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractChoiceFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('choices', TextareaType::class, [
				'label' => 'duo_form.form.form_part.choices.label',
				'attr' => [
					'rows' => 5
				]
			])
			->add('expanded', CheckboxType::class, [
				'label' => 'duo_form.form.form_part.expanded.label',
				'required' => false
			])
			->add('multiple', CheckboxType::class, [
				'label' => 'duo_form.form.form_part.multiple.label',
				'required' => false
			]);
	}
}
