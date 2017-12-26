<?php

namespace Duo\AdminBundle\Form;

use Duo\AdminBundle\Entity\Taxonomy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyAdminType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('translations', TranslationType::class, [
			'entry_type' => TaxonomyAdminTranslationType::class,
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