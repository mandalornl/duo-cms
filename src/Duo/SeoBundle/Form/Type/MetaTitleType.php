<?php

namespace Duo\SeoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaTitleType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'attr' => [
				'maxlength' => 60
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TextType::class;
	}
}
