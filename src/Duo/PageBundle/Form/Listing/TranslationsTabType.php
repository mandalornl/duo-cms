<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\AdminBundle\Form\Type\TranslationType;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class TranslationsTabType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('translations', TranslationType::class, [
			'entry_type' => PageTranslationType::class,
			'entry_options' => [
				'isNew' => !(is_object($options['data']) && $options['data']->getId() !== null)
			],
			'constraints' => [
				new Valid()
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefaults([
				'label' => 'duo_page.tab.translations',
				'data' => null
			])
			->setAllowedTypes('data', ['null', PageInterface::class]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
