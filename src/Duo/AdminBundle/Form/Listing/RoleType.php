<?php

namespace Duo\AdminBundle\Form\Listing;

use Duo\AdminBundle\Entity\Security\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'duo.form.role.name.label'
			])
			->add('role', TextType::class, [
				'label' => 'duo.form.role.role.label'
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Role::class
		]);
	}
}