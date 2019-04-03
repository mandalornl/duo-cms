<?php

namespace Duo\TranslatorBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertiesTabType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('keyword', TextType::class, [
				'label' => 'duo_translator.form.entry.keyword.label'
			])
			->add('domain', TextType::class, [
				'label' => 'duo_translator.form.entry.domain.label'
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_translator.tab.properties'
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
