<?php

namespace Duo\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;

class HoneypotType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_form.form.honeypot.label',
			'mapped' => false,
			'required' => false,
			'data' => '',
			'constraints' => [
				new Blank([
					'message' => 'duo_form.errors.honeypot'
				])
			],
			'error_bubbling' => true,
			'attr' => [
				'autocomplete' => 'off',
				'tabindex' => '-1'
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return EmailType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_honeypot';
	}
}
