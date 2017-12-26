<?php

namespace Duo\AdminBundle\Form;

use Duo\AdminBundle\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageAdminType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$weightRange = range(-100, 100);

		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'Properties'
				])
				->add('name', TextType::class)
				->add('weight', ChoiceType::class, [
					'choices' => array_combine($weightRange, $weightRange)
				])
			)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'Content'
				])
				->add('translations', TranslationType::class, [
					'entry_type' => PageAdminTranslationType::class,
					'allow_add' => false,
					'allow_delete' => false,
					'by_reference' => false
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
			'data_class' => Page::class
		]);
	}
}