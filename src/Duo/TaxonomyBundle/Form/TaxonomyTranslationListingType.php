<?php

namespace Duo\TaxonomyBundle\Form;

use Duo\TaxonomyBundle\Entity\TaxonomyTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaxonomyTranslationListingType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', TextType::class, [
			'label' => 'duo.taxonomy.form.taxonomy.name.label',
			'constraints' => [
				new NotBlank()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => TaxonomyTranslation::class
		]);
	}
}