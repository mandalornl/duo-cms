<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\TranslationType;
use Duo\AdminBundle\Form\WeightChoiceType;
use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Form\PageAutoCompleteType;
use Duo\TaxonomyBundle\Form\TaxonomyChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class PageType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.page.tab.content'
				])
				->add('translations', TranslationType::class, [
					'entry_type' => PageTranslationType::class,
					'entry_options' => [
						'isNew' => !(is_object($options['data']) && $options['data']->getId() !== null)
					],
					'constraints' => [
						new Valid()
					]
				])
			)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.page.tab.properties'
				])
				->add('name', TextType::class, [
					'label' => 'duo.page.form.page.name.label'
				])
				->add('weight', WeightChoiceType::class, [
					'label' => 'duo.page.form.page.weight.label',
					'required' => false
				])
				->add('taxonomies', TaxonomyChoiceType::class, [
					'label' => 'duo.page.form.page.taxonomies.label',
					'required' => false
				])
				->add('parent', PageAutoCompleteType::class, [
					'label' => 'duo.page.form.page.parent.label',
					'required' => false,
					'excludeSelf' => true
				])
			);

		$builder
			->add($tabs)
			->add('version', HiddenType::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => PageInterface::class
		]);
	}
}