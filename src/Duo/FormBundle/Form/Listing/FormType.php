<?php

namespace Duo\FormBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabsType;
use Duo\FormBundle\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add(
			$builder->create('tabs', TabsType::class)
				->add($builder->create('content', ContentTabType::class))
				->add($builder->create('properties', PropertiesTabType::class))
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Form::class
		]);
	}
}
