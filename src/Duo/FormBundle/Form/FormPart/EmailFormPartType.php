<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\EmailFormPart;
use Duo\FormBundle\Form\AbstractTextFormPartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => EmailFormPart::class,
			'model_class' => EmailFormPart::class
		]);
	}
}