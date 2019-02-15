<?php

namespace Duo\TaxonomyBundle\Form\Listing;

use Duo\TaxonomyBundle\Entity\TaxonomyTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyTranslationType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('name', TextType::class, [
			'label' => 'duo_taxonomy.form.taxonomy.name.label'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => TaxonomyTranslation::class
		]);
	}
}
