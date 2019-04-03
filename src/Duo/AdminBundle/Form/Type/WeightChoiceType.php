<?php

namespace Duo\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeightChoiceType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$choices = range(-100, 100);

		$resolver->setDefaults([
			'choices' => array_combine($choices, $choices),
			'placeholder' => 'duo_admin.form.weight_choice.placeholder',
			'choice_translation_domain' => false
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return ChoiceType::class;
	}
}
