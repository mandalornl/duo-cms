<?php

namespace Duo\FormBundle\Form\Type;

use Duo\AdminBundle\Form\Type\AutoCompleteType;
use Duo\FormBundle\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormAutoCompleteType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_form.form.form_autocomplete.label',
			'class' => Form::class,
			'routeName' => 'duo_form_autocomplete_form_name',
			'placeholder' => 'duo_form.form.form_autocomplete.placeholder',
			'propertyName' => 'name'
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return AutoCompleteType::class;
	}
}
