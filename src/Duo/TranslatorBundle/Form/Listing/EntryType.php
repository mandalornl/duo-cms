<?php

namespace Duo\TranslatorBundle\Form\Listing;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\TranslationType;
use Duo\TranslatorBundle\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class EntryType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.translator.tab.content'
				])
					->add('translations', TranslationType::class, [
						'entry_type' => EntryTranslationType::class,
						'constraints' => [
							new Valid()
						]
					])
			)
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