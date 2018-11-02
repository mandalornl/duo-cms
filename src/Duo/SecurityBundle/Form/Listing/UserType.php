<?php

namespace Duo\SecurityBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabsType;
use Duo\SecurityBundle\Entity\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add(
			$builder->create('tabs', TabsType::class)
				->add($builder->create('profile', ProfileTabType::class))
				->add($builder->create('properties', PropertiesTabType::class))
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => UserInterface::class
		]);
	}
}