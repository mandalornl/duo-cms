<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TextareaFormPart;
use Duo\FormBundle\Form\AbstractFormPartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextareaFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => TextareaFormPart::class,
			'model_class' => TextareaFormPart::class
		]);
	}
}