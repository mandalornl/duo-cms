<?php

namespace Duo\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => 'duo_security.errors.password_mismatch',
				'options' => [
					'attr' => [
						'autocomplete' => 'off'
					]
				],
				'constraints' => [
					new NotBlank()
				],
				'first_options' => [
					'label' => 'duo_security.form.reset_password.password.label'
				],
				'second_options' => [
					'label' => 'duo_security.form.reset_password.repeat_password.label'
				]
			])
			->add('submit', SubmitType::class, [
				'label' => 'duo_security.form.reset_password.submit.label',
				'attr' => [
					'class' => 'btn-primary btn-block'
				]
			]);
	}
}
