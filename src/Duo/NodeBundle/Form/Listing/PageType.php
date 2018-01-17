<?php

namespace Duo\NodeBundle\Form\Listing;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\TranslationType;
use Duo\AdminBundle\Form\WeightChoiceType;
use Duo\NodeBundle\Entity\Page;
use Duo\TaxonomyBundle\Form\TaxonomyChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.node.tab.content'
				])
				->add('translations', TranslationType::class, [
					'entry_type' => PageTranslationType::class,
					'entry_options' => [
						'isNew' => is_object($options['data']) ? $options['data']->getId() === null : true
					],
					'allow_add' => false,
					'allow_delete' => false,
					'by_reference' => false
				])
			)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.node.tab.properties'
				])
				->add('name', TextType::class, [
					'label' => 'duo.node.form.page.name.label'
				])
				->add('weight', WeightChoiceType::class, [
					'label' => 'duo.node.form.page.weight.label',
					'required' => false
				])
				->add('taxonomies', TaxonomyChoiceType::class, [
					'required' => false
				])
			);

		$builder
			->add($tabs)
			->add('version', HiddenType::class);
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