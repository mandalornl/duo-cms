<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TextFormPart;
use Duo\FormBundle\Form\AbstractFormPartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => TextFormPart::class,
			'model_class' => TextFormPart::class
		]);
	}
}