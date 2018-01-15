<?php

namespace Duo\PagePartBundle\Form;

use Duo\PagePartBundle\Entity\VideoPagePart;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoPagePartType extends AbstractPagePartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => VideoPagePart::class,
			'model_class' => VideoPagePart::class
		]);
	}
}