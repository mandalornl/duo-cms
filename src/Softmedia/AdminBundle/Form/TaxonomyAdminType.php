<?php

namespace Softmedia\AdminBundle\Form;

use Softmedia\AdminBundle\Entity\Taxonomy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyAdminType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('translations', CollectionType::class, [
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