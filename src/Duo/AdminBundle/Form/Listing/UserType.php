<?php

namespace Duo\AdminBundle\Form\Listing;

use Duo\AdminBundle\Entity\Security\User;
use Duo\AdminBundle\Form\ConfirmChoiceType;
use Duo\AdminBundle\Form\Security\GroupChoiceType;
use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
					'label' => 'duo.tab.profile'
				])
				->add('name', TextType::class, [
					'label' => 'duo.form.user.name.label'
				])
				->add('username', EmailType::class, [
					'label' => 'duo.form.user.username.label'
				])
			)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.tab.properties'
				])
				->add('active', ConfirmChoiceType::class, [
					'label' => 'duo.form.user.active.label'
				])
				->add('groups', GroupChoiceType::class, [
					'label' => 'duo.form.user.groups.label'
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