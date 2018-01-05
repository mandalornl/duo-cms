<?php

namespace Duo\AdminBundle\Form\Node;

use Duo\AdminBundle\Entity\Node\Page;
use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\TaxonomyChoiceType;
use Duo\AdminBundle\Form\TranslationType;
use Duo\AdminBundle\Form\WeightChoiceType;
use Symfony\Component\Form\AbstractType;
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
					'label' => 'duo.admin.tab.content'
				])
				->add('translations', TranslationType::class, [
					'entry_type' => PageTranslationType::class,
					'allow_add' => false,
					'allow_delete' => false,
					'by_reference' => false
				])
			)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.admin.tab.properties'
				])
				->add('name', TextType::class, [
					'label' => 'duo.admin.form.page.name.label'
				])
				->add('weight', WeightChoiceType::class, [
					'required' => false
				])
				->add('taxonomies', TaxonomyChoiceType::class, [
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
			'data_class' => Page::class
		]);
	}
}