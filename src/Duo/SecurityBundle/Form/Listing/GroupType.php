<?php

namespace Duo\SecurityBundle\Form\Listing;

use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Form\RoleChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'duo.security.form.group.name.label'
			])
			->add('roles', RoleChoiceType::class, [
				'label' => 'duo.security.form.group.roles.label'
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Group::class
		]);
	}
}