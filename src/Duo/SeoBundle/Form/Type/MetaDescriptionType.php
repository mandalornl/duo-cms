<?php

namespace Duo\SeoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaDescriptionType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'attr' => [
				'rows' => 3,
				'maxlength' => 300
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TextareaType::class;
	}
}
