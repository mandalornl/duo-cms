<?php

namespace Duo\AdminBundle\Form;

use Duo\AdminBundle\Entity\PageTranslation;
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
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'Content'
				])
				->add('title', TextType::class, [
					'required' => false
				])
				->add('content', TextareaType::class, [
					'required' => false
				])
			)
			->add(
				$builder->create('menu', TabType::class, [
					'label' => 'Menu'
				])
				->add('slug', TextType::class, [
					'required' => false,
					'disabled' => true
				])
				->add('url', TextType::class, [
					'required' => false,
					'disabled' => true
				])
			)
			->add(
				$builder->create('seo', TabType::class, [
					'label' => 'Seo'
				])
				->add('metaTitle', TextType::class, [
					'required' => false
				])
				->add('metaDescription', TextareaType::class, [
					'required' => false
				])
				->add('metaKeywords', TextType::class, [
					'required' => false
				])
			);

		$builder->add($tabs);
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