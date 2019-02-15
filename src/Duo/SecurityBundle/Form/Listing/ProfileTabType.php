<?php

namespace Duo\SecurityBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileTabType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'duo_security.form.user.name.label'
			])
			->add('email', EmailType::class, [
				'label' => 'duo_security.form.user.email.label',
				'attr' => [
					'autocomplete' => 'off'
				]
			])
			->add('username', TextType::class, [
				'label' => 'duo_security.form.user.username.label',
				'required' => false,
				'disabled' => true,
				'attr' => [
					'autocomplete' => 'off'
				]
			])
			->add('plainPassword', PasswordType::class, [
				'label' => 'duo_security.form.user.password.label',
				'required' => false,
				'attr' => [
					'autocomplete' => 'off'
				]
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_security.tab.profile'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
