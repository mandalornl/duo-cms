<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\SubmitFormPart;
use Duo\FormBundle\Form\AbstractFormPartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => SubmitFormPart::class,
			'model_class' => SubmitFormPart::class
		]);
	}
}