<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\ImagePagePart;
use Duo\MediaBundle\Form\MediaType;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagePagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('media', MediaType::class, [
			'label' => false,
			'mediaType' => 'image'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => ImagePagePart::class,
			'model_class' => ImagePagePart::class
		]);
	}
}