<?php

namespace Duo\AdminBundle\Form\Listing;

use Duo\AdminBundle\Form\ConfirmChoiceType;
use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\SecurityBundle\Entity\User;
use Duo\SecurityBundle\Form\GroupChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('profile', TabType::class, [
					'label' => 'duo.admin.tab.profile'
				])
				->add('name', TextType::class, [
					'label' => 'duo.admin.form.user.name.label'
				])
				->add('email', EmailType::class, [
					'label' => 'duo.admin.form.user.email.label'
				])
				->add('username', TextType::class, [
					'label' => 'duo.admin.form.user.username.label',
					'required' => false,
					'disabled' => true
				])
				->add('plainPassword', PasswordType::class, [
					'label' => 'duo.admin.form.user.password.label',
					'required' => false
				])
			)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.admin.tab.properties'
				])
				->add('active', ConfirmChoiceType::class, [
					'label' => 'duo.admin.form.user.active.label'
				])
				->add('groups', GroupChoiceType::class, [
					'label' => 'duo.admin.form.user.groups.label'
				])
			);

		$builder->add($tabs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class
		]);
	}
}