<?php

namespace Duo\TranslatorBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabsType;
use Duo\TranslatorBundle\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Entry::class
		]);
	}
}