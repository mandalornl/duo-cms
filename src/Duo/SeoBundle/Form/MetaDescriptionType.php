<?php

namespace Duo\SeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class MetaDescriptionType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'attr' => [
				'rows' => 3,
				'maxlength' => 155
			],
			'constraints' => [
				new Length([
					'max' => 155
				])
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TextareaType::class;
	}
}