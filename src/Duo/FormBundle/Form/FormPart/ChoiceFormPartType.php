<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\ChoiceFormPart;
use Duo\FormBundle\Form\AbstractChoiceFormPartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceFormPartType extends AbstractChoiceFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => ChoiceFormPart::class,
			'model_class' => ChoiceFormPart::class
		]);
	}
}