<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\VideoPagePart;
use Duo\PageBundle\Form\AbstractPagePartType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoPagePartType extends AbstractPagePartType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => VideoPagePart::class,
			'model_class' => VideoPagePart::class
		]);
	}
}