<?php

namespace Duo\TaxonomyBundle\Form\Listing;

use Duo\AdminBundle\Form\TranslationType;
use Duo\TaxonomyBundle\Entity\Taxonomy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('translations', TranslationType::class, [
			'entry_type' => TaxonomyTranslationType::class,
			'allow_add' => false,
			'allow_delete' => false,
			'by_reference' => false
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Taxonomy::class
		]);
	}
}