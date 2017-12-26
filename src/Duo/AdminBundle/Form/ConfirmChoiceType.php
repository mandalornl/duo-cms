<?php

namespace Duo\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmChoiceType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'choices' => [
				'duo.form.confirm_choice.choices.yes' => 1,
				'duo.form.confirm_choice.choices.no' => 0
			],
			'data' => 1
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return ChoiceType::class;
	}
}