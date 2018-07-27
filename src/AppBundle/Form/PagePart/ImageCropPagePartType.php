<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\ImageCropPagePart;
use Duo\MediaBundle\Form\ImageCropType;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ImageCropPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('imageCrop', ImageCropType::class, [
			'label' => false,
			'constraints' => [
				new Valid()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => ImageCropPagePart::class,
			'model_class' => ImageCropPagePart::class
		]);
	}
}