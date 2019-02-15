<?php

namespace Duo\AdminBundle\Form\Filter;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class BooleanFilterType extends AbstractFilterType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('value', ChoiceType::class, [
			'required' => false,
			'empty_data' => null,
			'placeholder' => 'duo_admin.form.boolean_filter.placeholder',
			'choices' => [
				'duo_admin.form.boolean_filter.choices.yes' => 1,
				'duo_admin.form.boolean_filter.choices.no' => 0
			]
		]);
	}
}
