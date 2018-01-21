<?php

namespace Duo\FormBundle\Form;

use Duo\AdminBundle\Form\AutoCompleteType;
use Duo\FormBundle\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormAutoCompleteType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'label' => 'duo.form.form.form_autocomplete.label',
			'class' => Form::class,
			'routeName' => 'duo_form_autocomplete_form_name',
			'placeholder' => 'duo.form.form.form_autocomplete.placeholder',
			'propertyName' => 'name'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return AutoCompleteType::class;
	}
}