<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TextFormPart;
use Duo\FormBundle\Form\AbstractTextFormPartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => TextFormPart::class,
			'model_class' => TextFormPart::class
		]);
	}
}