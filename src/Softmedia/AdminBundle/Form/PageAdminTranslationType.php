<?php

namespace Softmedia\AdminBundle\Form;

use Softmedia\AdminBundle\Entity\PageTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageAdminTranslationType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title', TextType::class)
			->add('content', TextareaType::class)
			->add('metaTitle', TextType::class)
			->add('metaDescription', TextareaType::class)
			->add('metaKeywords', TextType::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => PageTranslation::class
		]);
	}
}