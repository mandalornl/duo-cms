<?php

namespace Duo\MediaBundle\Form;

use Duo\MediaBundle\Entity\ImageCrop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageCropType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$mediaOptions = array_replace_recursive($options['mediaOptions'], [
			'label' => false,
			'mediaType' => 'image'
		]);

		$ratioOptions = array_replace_recursive([
			'choices' => [
				'duo.media.form.image_crop.ratio.choices.landscape_4_3' => '4:3',
				'duo.media.form.image_crop.ratio.choices.portrait_4_3' => '3:4',
				'duo.media.form.image_crop.ratio.choices.landscape_5_4' => '5:4',
				'duo.media.form.image_crop.ratio.choices.portrait_4_5' => '4:5',
				'duo.media.form.image_crop.ratio.choices.landscape_3_2' => '3:2',
				'duo.media.form.image_crop.ratio.choices.portrait_2_3' => '2:3',
				'duo.media.form.image_crop.ratio.choices.landscape_2_1' => '2:1',
				'duo.media.form.image_crop.ratio.choices.portrait_1_2' => '1:2',
				'duo.media.form.image_crop.ratio.choices.landscape_16_9' => '16:9',
				'duo.media.form.image_crop.ratio.choices.portrait_9_16' => '9:16',
				'duo.media.form.image_crop.ratio.choices.landscape_21_9' => '21:9',
				'duo.media.form.image_crop.ratio.choices.portrait_9_21' => '9:21',
				'duo.media.form.image_crop.ratio.choices.square' => '1:1'
			]
		], $options['ratioOptions'], [
			'label' => false
		]);

		$ratioFormType = ChoiceType::class;

		if (count($ratioOptions['choices']) === 1)
		{
			$ratioFormType = HiddenType::class;

			$ratioOptions['data'] = array_values($ratioOptions['choices'])[0];

			unset($ratioOptions['choices']);
		}

		$builder
			->add('crop', HiddenType::class)
			->add('media', MediaType::class, $mediaOptions)
			->add('ratio', $ratioFormType, $ratioOptions);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => ImageCrop::class,
			'mediaOptions' => [],
			'ratioOptions' => []
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_image_crop';
	}
}