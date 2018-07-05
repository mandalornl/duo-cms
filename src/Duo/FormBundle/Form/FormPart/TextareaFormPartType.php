<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TextareaFormPart;
use Duo\FormBundle\Form\AbstractTextFormPartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextareaFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => TextareaFormPart::class,
			'model_class' => TextareaFormPart::class
		]);
	}
}