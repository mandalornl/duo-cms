<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\ImageCropPagePart;
use Duo\MediaBundle\Form\Type\ImageCropType;
use Duo\PartBundle\Form\Type\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

class ImageCropPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('imageCrop', ImageCropType::class, [
				'label' => false,
				'constraints' => [
					new Valid()
				]
			])->add('altText', TextType::class, [
				'required' => false,
				'label' => 'app.form.image_crop_page_part.alt.label'
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return ImageCropPagePart::class;
	}
}