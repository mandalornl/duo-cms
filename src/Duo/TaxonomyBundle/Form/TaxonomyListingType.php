<?php

namespace Duo\TaxonomyBundle\Form;

use Duo\AdminBundle\Form\TranslationType;
use Duo\TaxonomyBundle\Entity\Taxonomy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class TaxonomyListingType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('translations', TranslationType::class, [
			'entry_type' => TaxonomyTranslationListingType::class,
			'allow_add' => false,
			'allow_delete' => false,
			'by_reference' => false,
			'constraints' => [
				new Valid()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Taxonomy::class
		]);
	}
}