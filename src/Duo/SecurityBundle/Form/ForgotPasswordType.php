<?php

namespace Duo\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgotPasswordType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', EmailType::class, [
				'label' => 'duo.security.form.forgot_password.email.label',
				'attr' => [
					'autocomplete' => 'off'
				],
				'constraints' => [
					new NotBlank(),
					new Email()
				]
			])
			->add('submit', SubmitType::class, [
				'label' => 'duo.security.form.forgot_password.submit.label',
				'attr' => [
					'class' => 'btn-primary btn-block'
				]
			]);
	}
}