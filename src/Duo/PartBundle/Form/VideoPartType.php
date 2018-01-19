<?php

namespace Duo\PartBundle\Form;

use Duo\PartBundle\Entity\VideoPart;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoPartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => VideoPart::class,
			'model_class' => VideoPart::class
		]);
	}
}