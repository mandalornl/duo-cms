<?php

namespace Duo\TranslatorBundle\Form;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\TranslationType;
use Duo\TranslatorBundle\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class EntryListingType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.translator.tab.properties'
				])
				->add('keyword', TextType::class, [
					'label' => 'duo.translator.form.entry.keyword.label'
				])
				->add('domain', TextType::class, [
					'label' => 'duo.translator.form.entry.domain.label'
				])
			)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.translator.tab.content'
				])
				->add('translations', TranslationType::class, [
					'entry_type' => EntryTranslationListingType::class,
					'constraints' => [
						new Valid()
					]
				])
			);

		$builder->add($tabs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Entry::class
		]);
	}
}