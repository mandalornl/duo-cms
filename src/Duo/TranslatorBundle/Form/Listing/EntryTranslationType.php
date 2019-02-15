<?php

namespace Duo\TranslatorBundle\Form\Listing;

use Duo\TranslatorBundle\Entity\EntryTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryTranslationType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('text', TextareaType::class, [
			'label' => 'duo_translator.form.entry_translation.text.label',
			'attr' => [
				'rows' => 6
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => EntryTranslation::class
		]);
	}
}
